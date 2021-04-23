<?php

namespace App\Http\Controllers;

use App\Models\{Book, Author, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Book $book)
    {
        // $this->authorize('viewAny', Book::class);
        return view('book/index', array('books' => Book::get()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $book_categories = collect(
            array(
                'Komik', 'Aksi', 'Romantis', 'Petualangan', 'Drama',
                'Komedi', 'Horror', 'Tentara', 'Kriminal', 'Fiksi Ilmiah',
                'Fantasi', 'Misteri', 'Biografi', 'Ensiklopedia', 'Kamus',
                'Jurnal', 'Filsafat',
            )
        );

        return view('book/create', compact('book_categories'));
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
                'name'          => array('required', 'unique:books'),
                'price'         => array('required', 'integer'),
                'image'         => array('required', 'file', 'max:2000', 'mimes:jpg'),
                'author_change' => array('required'),
                'categories'    => array('required'),
                'synopsis'      => array('required'),
            )
        );

        $validate_data['image'] = $validate_data['image']->getClientOriginalName();
        $author                 = Author::where('name', $validate_data['author_change'])->first();

        if (!$author) {
            $author = Author::create(
                array(
                    'id' => $validate_data['author_change']
                )
            );

        }

        $book =  Book::create(
            array(
                'name'      => $validate_data['name'],
                'price'     => $validate_data['price'],
                'image'     => $validate_data['image'],
                'author_id' => $author->id,
            )
        );

        $author->books()->attach(Book::find($book->id));

        foreach ($validate_data['categories'] as $category) {
            $book->categories()->create(
                array(
                    'name'    => $category,
                    'book_id' => $book->id,
                )
            );
        }

        $book->synopsis()->create(
            array(
                'text'    => $validate_data['synopsis'],
                'book_id' => $book->id,
            )
        );

        $pesan = 'Berhasil menambah buku ' . $book->name;

        return redirect()->route('books.index')->with('pesan', $pesan);
        dump($validate_data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return view('book.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $book_categories = collect(
            array(
                'Komik', 'Aksi', 'Romantis', 'Petualangan', 'Drama',
                'Komedi', 'Horror', 'Tentara', 'Kriminal', 'Fiksi Ilmiah',
                'Fantasi', 'Misteri', 'Biografi', 'Ensiklopedia', 'Kamus',
                'Jurnal', 'Filsafat',
            )
        );

        $authors = Author::get();

        return view('book.edit', compact('book', 'book_categories', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $validate_data = $request->validate(
            array(
                'name'        => array('required', 'unique:books,name,' . $book->id),
                'price'       => array('required', 'integer'),
                'image'       => array('required', 'file', 'max:2000', 'mimes:jpg'),
                'author_change' => array('required'),
                'categories'  => array('required'),
                'synopsis'    => array('required'),
            )
        );

        $validate_data['image'] = $validate_data['image']->getClientOriginalName();

        $nama_buku = $book->name;

        $book->categories()->delete();

        foreach ($validate_data['categories'] as $category) {
            $book->categories()->create(

                array(
                    'name'    => $category,
                    'book_id' => $book->id,
                )
            );
        }

        $book->update(
            array(
                'name'  => $validate_data['name'],
                'price' => $validate_data['price'],
                'image' => $validate_data['image'],
            )
        );

        $book->synopsis()->update(
            array(
                'text'    => $validate_data['synopsis'],
                'book_id' => $book->id,
            )
        );

        $book->authors[0]->pivot->update(
            array(
                'author_id' => $validate_data['author_change'],
            )
        );

        $pesan = 'Berhasil mengubah data ' . $nama_buku;

        return redirect()->route('books.index')->with('pesan', $pesan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $pesan = 'Berhasil menghapus buku ' . $book->name;
        $book->authors()->delete();

        return redirect()->route('books.index')->with('pesan', $pesan);
    }

    // Search Book
    public function searchBooks() {
        return view('book/search-books');
    }

    // Beli buku
    public function booksBuy () {
        return view('book/buy');
    }
}
