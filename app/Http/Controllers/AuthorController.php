<?php

namespace App\Http\Controllers;

use App\Models\{Author, Book, Category};
use Illuminate\Database\Eloquent\Builder;
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
        $between    = [(int) $request->min_price, (int) $request->max_price == 0 ? 99999999 : $request->max_price];
        $category   = Category::get();

        switch ($request->sort) {
            case 'relevan':
                if ($request->category) {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->whereIn('category_id', $request->category ?? [1, $category->count()])
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                } else {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                }

                $authors = Author::whereHas('books', function (Builder $query) {
                    global $request;
                    $between    = [(int) $request->min_price, (int) $request->max_price == 0 ? 99999999 : $request->max_price];

                    if ($request->category) {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereIn('category_id', $request->category)
                            ->whereBetween('price', $between);
                    } else {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereBetween('price', $between);
                    }
                });
                break;
            case 'lowest-rating':
                if ($request->category) {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->whereIn('category_id', $request->category ?? [1, $category->count()])
                        ->whereBetween('price', $between)
                        ->orderBy('rating')
                        ->get()
                        ->count();
                } else {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->orderBy('rating')
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                }

                $authors = Author::whereHas('books', function (Builder $query) {
                    global $request;

                    $between    = [(int) $request->min_price, (int) $request->max_price == 0 ? 99999999 : $request->max_price];

                    if ($request->category) {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereIn('category_id', $request->category)
                            ->orderBy('rating')
                            ->whereBetween('price', $between);
                    } else {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereBetween('price', $between);
                    }
                });
                break;
            case 'highest-rating':
                if ($request->category) {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->whereIn('category_id', $request->category ?? [1, $category->count()])
                        ->orderByDesc('rating')
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                } else {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->orderByDesc('rating')
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                }

                $authors = Author::whereHas('books', function (Builder $query) {
                    global $request;
                    $between    = [(int) $request->min_price, (int) $request->max_price == 0 ? 99999999 : $request->max_price];

                    if ($request->category) {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereIn('category_id', $request->category)
                            ->orderByDesc('rating')
                            ->whereBetween('price', $between);
                    } else {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereBetween('price', $between);
                    }
                });
                break;
            case 'lowest-price':
                if ($request->category) {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->whereIn('category_id', $request->category ?? [1, $category->count()])
                        ->orderBy('price')
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                } else {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->orderBy('price')
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                }

                $authors = Author::whereHas('books', function (Builder $query) {
                    global $request;
                    $between    = [(int) $request->min_price, (int) $request->max_price == 0 ? 99999999 : $request->max_price];

                    if ($request->category) {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereIn('category_id', $request->category)
                            ->orderBy('price')
                            ->whereBetween('price', $between);
                    } else {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereBetween('price', $between);
                    }
                });
                break;
            case 'highest-price':
                if ($request->category) {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->whereIn('category_id', $request->category ?? [1, $category->count()])
                        ->orderByDesc('price')
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                } else {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->orderByDesc('price')
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                }

                $authors = Author::whereHas('books', function (Builder $query) {
                    global $request;

                    $between    = [(int) $request->min_price, (int) $request->max_price == 0 ? 99999999 : $request->max_price];

                    if ($request->category) {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereIn('category_id', $request->category)
                            ->orderByDesc('price')
                            ->whereBetween('price', $between);
                    } else {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereBetween('price', $between);
                    }
                });
                break;

            default:
                if ($request->category) {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->whereIn('category_id', $request->category ?? [1, $category->count()])
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                } else {
                    $book_count = Book::where('name',   'LIKE', "%$request->keywords%")
                        ->whereBetween('price', $between)
                        ->get()
                        ->count();
                }

                $authors = Author::whereHas('books', function (Builder $query) {
                    global $request;
                    $between    = [(int) $request->min_price, (int) $request->max_price == 0 ? 99999999 : $request->max_price];

                    if ($request->category) {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereIn('category_id', $request->category)
                            ->whereBetween('price', $between);
                    } else {
                        $query
                            ->where('name',   'LIKE', "%$request->keywords%")
                            ->whereBetween('price', $between);
                    }

                    $query->where('name', 'LIKE', "%$request->keywords%")->whereBetween('price', $between);
                });

                break;
        }

        $authors = $authors->paginate(12)->withQueryString();

        return view('author.index', compact('authors', 'book_count'));
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
