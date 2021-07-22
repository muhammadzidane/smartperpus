<?php

namespace App\Http\Controllers;

use App\Models\{Book, Author, Category, BookPurchase, User, BookUser};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Validator, Auth, Date};

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

        $validator = Validator::make(
            $request->all(),
            array(
                'nama_penulis'       => array('required', 'min:3'),
                'isbn'               => array('required', 'numeric', 'digits:13', 'unique:books,isbn'),
                'judul_buku'         => array('required', 'unique:books,name'),
                'sinopsis'           => array('required'),
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
            )
        );

        $author     = Author::firstWhere('name', $request->nama_penulis) ?? Author::create(array('name' => $request->nama_penulis));
        $gambar_sampul_buku = $request->gambar_sampul_buku !== null ? $request->gambar_sampul_buku->getClientOriginalName() : null;

        $create = array(
            'isbn'         => $request->isbn,
            'name'         => $request->judul_buku,
            'price'        => (int) $request->price,
            'image'        => strtolower(str_replace(' ', '_', $gambar_sampul_buku)),
            'author_id'    => $author->id,
            'rating'       => 0.0,
            'discount'     => $request->tambah_diskon,
            'ebook'        => $request->tersedia_dalam_ebook,
            'pages'        => (int) $request->jumlah_halaman,
            'release_date' => $request->tanggal_rilis,
            'publisher'    => $request->penerbit,
            'subtitle'     => $request->subtitle,
            'weight'       => (int) $request->berat,
            'width'        => (float) $request->panjang,
            'height'       => (float) $request->lebar,
        );

        // Buat buku
        if (!$request->ajax()) {
            if (!$validator->fails()) {
                $book   = Book::create($create);

                $book->categories()->attach(Category::firstWhere('name', $request->kategori));
                $book->printedStock()->create(array('amount' => $request->jumlah_barang));
                $book->synopsis()->create(array('text' => $request->sinopsis));
                $request->gambar_sampul_buku->storeAs(
                    'public/books',
                    str_replace(' ', '_', strtolower($request->gambar_sampul_buku->getClientOriginalName()))
                );

                $pesan = 'Berhasil menambah buku ' . $request->judul_buku;

                return redirect()->back()->with('pesan', $pesan);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            $gambar_sampul_buku = str_replace('C:\fakepath\\', '', $request->gambar_sampul_buku);

            if ($validator->fails()) {
                return response()->json(array('gambar' => $gambar_sampul_buku, 'errors' => $validator->errors()));
            }
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
        $rules = array(
            'isbn'               => array('required', 'digits:13', 'unique:books,isbn,' . $book->id),
            'nama_penulis'       => array('required'),
            'judul_buku'         => array('required', 'unique:books,name,' . $book->id),
            'sinopsis'           => array('required'),
            'price'              => array('required', 'numeric'),
            'diskon'             => array('nullable', 'numeric'),
            'jumlah_barang'      => array('required', 'numeric'),
            'penerbit'           => array('required'),
            'jumlah_halaman'     => array('required', 'numeric'),
            'tanggal_rilis'      => array('required', 'date'),
            'subtitle'           => array('required'),
            'berat'              => array('required', 'numeric'),
            'panjang'            => array('required', 'numeric'),
            'lebar'              => array('required', 'numeric'),
            'gambar_sampul_buku' => array('nullable', 'mimes:png,jpg,jpeg', 'max:2000'),
        );

        $book_image_name = $request->gambar_sampul_buku != null ? $request->gambar_sampul_buku->getClientOriginalName() : $book->image;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(compact('errors'));
        } else {
            if ($request->gambar_sampul_buku && !Storage::exists('public/books/' . $book_image_name)) {
                $request->gambar_sampul_buku->storeAs('public/books', $book_image_name);
            }

            $create = array('name' => $request->nama_penulis);
            $author = Author::firstWhere('name', $request->nama_penulis) ?? Author::create($create);
            $update = array(
                'isbn'               => $request->isbn,
                'category_id'        => $request->kategori,
                'printed_book_stock' => $request->jumlah_barang,
                'name'               => $request->judul_buku,
                'price'              => $request->price,
                'image'              => $book_image_name,
                'author_id'          => $author->id,
                'discount'           => $request->diskon,
                'ebook'              => $request->tersedia_dalam_ebook,
                'pages'              => $request->jumlah_halaman,
                'release_date'       => $request->tanggal_rilis,
                'publisher'          => $request->penerbit,
                'subtitle'           => $request->subtitle,
                'weight'             => $request->berat,
                'width'              => $request->panjang,
                'height'             => $request->lebar,
            );

            $book->update($update);

            return response()->json()->status();
        }
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

        if ($book->delete()) {
            Storage::delete('public/storage/books/' . $book->image);
            unlink(storage_path('app/
            ' . $book->image));

            return redirect()->route('home')->with('pesan', $pesan);
        }
    }

    // Search Book
    public function searchBooks()
    {
        return view('book/search-books');
    }

    // Beli buku
    public function booksBuy($book)
    {
        $book         = Book::firstWhere('name', $book);
        $user         = Auth::user();
        $book_version = $book->ebook ? 'ebook' : 'hard_cover';

        return view('book/buy', compact('book', 'user', 'book_version'));
    }

    // Keranjang Belanja
    public function shoppingCart()
    {
        return view('book/shopping-cart');
    }

    public function wishlist()
    {
        return view('book/wishlist');
    }

    public function addDiscount()
    {
        return 'diskon diambahkan';
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function addStock(Request $request, Book $book)
    {
        $rules = array(
            'stock' => array('required', 'numeric'),
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json(compact('errors'));
        } else {
            $stock  = $book->printed_book_stock + $request->stock;
            $update = array('printed_book_stock' => $stock);

            $book->update($update);

            $stock  = $request->stock;

            return response()->json(compact('stock'));
        }
    }
}
