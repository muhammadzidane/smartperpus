<?php

namespace App\Http\Controllers;

use App\Models\{Author, Book, Category};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authors = Author::where('authors.name', 'LIKE', "%$request->keywords%")
            ->paginate(12)
            ->withQueryString();

        $conditions = array(
            array('books.name', 'LIKE', "%$request->keywords%"),
            array('authors.name', 'LIKE', "%$request->keywords%", 'OR'),
        );

        $books = Book::join('authors', 'books.author_id', '=', 'authors.id')
            ->select('books.*', 'authors.name as author_name')
            ->where($conditions);

        if ($request->category) $books = $books->whereIn('category_id', $request->category);

        if ($request->min_price || $request->max_price) {
            $between = array($request->min_price ?? 0, $request->max_price ?? $books->max('books.price'));
            $books   = $books->whereBetween('books.price', $between);
        }

        if ($request->sort == 'highest-price') $books = $books->orderByDesc('price');
        if ($request->sort == 'highest-rating') $books = $books->orderByDesc('rating');
        if ($request->sort == 'lowest-rating') $books = $books->orderBy('rating');
        if ($request->sort == 'lowest-price') $books = $books->orderBy('price');

        $total_books = $books->count();

        $data = compact('authors', 'total_books');

        return view('author.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate_data = $request->validate(
            array(
                'name'      => array('required'),
                'price'     => array('required', 'integer'),
                'image'     => array('required', 'file', 'max:2000', 'mimes:jpg'),
                'author_id' => array('required'),
                'genre'     => array('required'),
            )
        );

        $validate_data['image'] = $validate_data['image']->getClientOriginalName();

        // Author::create(
        //     array(
        //         'name' => $validate_data['author_id']
        //     )
        // );

        // $author = Author::where('name', $validate_data['author_id'])->first();

        // $author->books->create(
        //     array(
        //         'name'      => $validate_data['name'],
        //         'price'     => $validate_data['price'],
        //         'image'     => $validate_data['image'],
        //         'author_id' => $author->id,
        //     )
        // );

        // return 'berhasil menambah buku ';
        dump($validate_data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        $paginate = Book::where('author_id', $author->id)->paginate(36);

        return view('author.show', compact('author', 'paginate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        return view('author.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {


        $validate_data = $request->validate(
            array(
                'name' => array('required', 'unique:authors,name,' . $author->id),
            )
        );

        $author->update(
            array(
                'name' => $validate_data['name'],
            )
        );

        $pesan = 'Berhasil edit author ' . $author->name;
        return redirect()->route('authors.index')->with('pesan', $pesan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        //
    }
}
