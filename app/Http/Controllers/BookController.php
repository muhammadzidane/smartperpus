<?php

namespace App\Http\Controllers;

use App\Models\{Book, Author, Category, Synopsis};
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Book $book)
    {
        $this->authorize('viewAny', $book);
        return view('book/index', array('books' => Book::get()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('viewAny', Book::class);

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
        $this->authorize('create', Book::class);

        $validate_data = $request->validate(array(
            'nama_penulis'       => array('required', 'min:3', 'unique:authors,name'),
            'isbn'               => array('required', 'numeric', 'digits:13', 'unique:books,isbn'),
            'judul_buku'         => array('required', 'unique:books,name'),
            'sinopsis'           => array('required', 'unique:synopses,text'),
            'price'              => array('required', 'numeric'),
            'jumlah_barang'      => array('required', 'numeric'),
            'penerbit'           => array('required'),
            'jumlah_halaman'     => array('required', 'numeric'),
            'tanggal_rilis'      => array('required', 'date'),
            'subtitle'           => array('required'),
            'berat'              => array('required', 'numeric'),
            'panjang'            => array('required', 'numeric'),
            'lebar'              => array('required', 'numeric'),
            'gambar_sampul_buku' => array('required', 'file', 'image', 'mimes:jpg,png', 'unique:books,image', 'max:2000'),
        ));

        $author     = Author::create(array('name' => $request->nama_penulis));

        $validate_data = array(
            'isbn'         => $validate_data['isbn'],
            'name'         => $validate_data['judul_buku'],
            'price'        => (int) $validate_data['price'],
            'image'        => strtolower(str_replace(' ', '_', $validate_data['gambar_sampul_buku']->getClientOriginalName())),
            'author_id'    => $author->id,
            'rating'       => 0.0,
            'discount'     => null,
            'ebook'        => $request->tersedia_dalam_ebook,
            'pages'        => (int) $validate_data['jumlah_halaman'],
            'release_date' => $validate_data['tanggal_rilis'],
            'publisher'    => $validate_data['penerbit'],
            'subtitle'     => $validate_data['subtitle'],
            'weight'       => (int) $validate_data['berat'],
            'width'        => (float) $validate_data['lebar'],
            'height'       => (float) $validate_data['panjang'],
        );

        // Buat buku
        if (!in_array($validate_data, array(null))) {
            $book = Book::create($validate_data);

            $book->categories()->attach(Category::firstWhere('name', $request->kategori));
            $book->printedStock()->create(array('amount' => $request->jumlah_barang));
            $book->synopsis()->create(array('text' => $request->sinopsis));
            $author->books()->attach(Book::find($book->id));
            $request->gambar_sampul_buku->storeAs('public/books',
              str_replace(' ', '_', strtolower($request->gambar_sampul_buku->getClientOriginalName())));

            $pesan = 'Berhasil menambah buku ' . $request->judul_buku;

            return redirect()->back()->with('pesan', $pesan);;
        }
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
        $validate_data = $request->validate(array(
            'nama_penulis'       => array('required', 'min:3', 'unique:authors,name,' . $book->authors[0]->id),
            'isbn'               => array('required', 'numeric', 'digits:13', 'unique:books,isbn,' . $book->id),
            'judul_buku'         => array('required', 'unique:books,name,' . $book->id),
            'sinopsis'           => array('required', 'unique:synopses,text,' . $book->id),
            'price'              => array('required', 'numeric'),
            'jumlah_barang'      => array('required', 'numeric'),
            'penerbit'           => array('required'),
            'jumlah_halaman'     => array('required', 'numeric'),
            'tanggal_rilis'      => array('required', 'date'),
            'subtitle'           => array('required'),
            'berat'              => array('required', 'numeric'),
            'panjang'            => array('required', 'numeric'),
            'lebar'              => array('required', 'numeric'),
            'gambar_sampul_buku' => array('required', 'file', 'image', 'mimes:jpg,png', 'unique:books,image', 'max:2000'),
        ));

        $update = array(
            'isbn'         => $validate_data['isbn'],
            'name'         => $validate_data['judul_buku'],
            'price'        => (int) $validate_data['price'],
            'image'        => strtolower(str_replace(' ', '_', $validate_data['gambar_sampul_buku']->getClientOriginalName())),
            'author_id'    => $book->authors[0]->id,
            'rating'       => 0.0,
            'discount'     => null,
            'ebook'        => $request->tersedia_dalam_ebook,
            'pages'        => (int) $validate_data['jumlah_halaman'],
            'release_date' => $validate_data['tanggal_rilis'],
            'publisher'    => $validate_data['penerbit'],
            'subtitle'     => $validate_data['subtitle'],
            'weight'       => (int) $validate_data['berat'],
            'width'        => (float) $validate_data['lebar'],
            'height'       => (float) $validate_data['panjang'],
        );

        $book->update($update);
        $book->synopsis()->update(array('text' => $validate_data['sinopsis']));
        $book->categories()->update(array('category_id' => Category::firstWhere('name', $request->kategori)->id));
        $book->authors()->update(array('name' => $request->nama_penulis));
        $book->printedStock()->update(array('amount' => $request->jumlah_barang));

        $pesan = 'Berhasil men-update buku ' . $request->judul_buku;

        return redirect()->back()->with('pesan', $pesan);
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
    public function booksBuy($book) {
        $book = Book::firstWhere('name', $book);

        return view('book/buy', compact(('book')));
    }

    // Pembelian Buku
    public function booksPayment() {
        return view('book/book-payment');
    }

    // Keranjang Belanja
    public function shoppingCart() {
        return view('book/shopping-cart');
    }

    public function wishlist() {
        return view('book/wishlist');
    }

    public function addDiscount() {
        return 'diskon diambahkan';
    }
}

