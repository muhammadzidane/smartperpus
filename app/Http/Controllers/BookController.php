<?php

namespace App\Http\Controllers;

use App\Models\{Book, Author, Category, BookImage, Rating};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\{Storage, Validator, Auth, File, DB};

class BookController extends Controller
{
    // Middleware
    public function __construct()
    {
        $methods = array('edit', 'create', 'store');

        $this->middleware('auth.admin.only')->only($methods);
        $this->middleware('strip.empty.param')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $conditions = array(
            array('books.name', 'LIKE', "%$request->keywords%"),
            array('authors.name', 'LIKE', "%$request->keywords%", 'OR'),
        );

        $rating = $request->sort == 'lowest-rating'
            ? DB::raw('IFNULL(ratings.rating, 999) as rating')
            : 'ratings.rating as rating'; // highest-rating

        $select_values = array(
            'books.*',
            $rating,
        );

        $books = Book::leftJoin('ratings', 'books.id', '=', 'ratings.book_id')
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->select($select_values)
            ->where($conditions);

        $authors = Author::join('books', 'authors.id', '=', 'books.author_id')
            ->select('authors.*')
            ->distinct('authors.id')
            ->where('authors.name', 'LIKE', "%$request->keywords%");

        $categories = Category::join('books', 'categories.id', '=', 'books.category_id')
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->select('categories.*', DB::raw('count(books.category_id) as total_books'))
            ->groupBy('categories.id')
            ->where($conditions);

        if ($request->category) $books = $books->whereIn('category_id', $request->category);

        if ($request->min_price || $request->max_price) {
            $between = array($request->min_price ?? 0, $request->max_price ?? Book::max('price'));
            $books   = $books->whereBetween('books.price', $between);
        }

        if ($request->sort == 'highest-price') $books = $books->orderByDesc('price');
        if ($request->sort == 'lowest-price') $books = $books->orderBy('price');

        if ($request->sort == 'highest-rating')
            $books = $books->orderByDesc('rating');
        if ($request->sort == 'lowest-rating')
            $books = $books->orderBy('rating');

        if ($request->discount == 'all') $books = $books->where('books.discount', '!=', 0);

        $books      = $books->paginate(40)->withQueryString();
        $categories = $categories->get();
        $data       = compact('books', 'categories', 'authors');

        return view('book.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $book_categories = Category::get();

        return view('book.edit', compact('book_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'isbn'               => array('required', 'digits:13', 'unique:books,isbn'),
            'nama_penulis'       => array('required'),
            'judul_buku'         => array('required', 'unique:books,name'),
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
            'gambar_sampul_buku' => array('required', 'mimes:png,jpg,jpeg', 'max:2000'),
        );

        $request->validate($rules);

        $book_image_name = $request->gambar_sampul_buku->getClientOriginalName();
        $request->gambar_sampul_buku->storeAs('public/books', $book_image_name);
        $create = array('name' => $request->nama_penulis);
        $author = Author::firstWhere('name', $request->nama_penulis) ?? Author::create($create);

        $create = array(
            'isbn'               => $request->isbn,
            'category_id'        => $request->kategori,
            'printed_book_stock' => $request->jumlah_barang,
            'name'               => $request->judul_buku,
            'price'              => $request->price,
            'image'              => $book_image_name,
            'author_id'          => $author->id,
            'discount'           => $request->diskon ?? 0,
            'ebook'              => $request->tersedia_dalam_ebook,
            'pages'              => $request->jumlah_halaman,
            'release_date'       => $request->tanggal_rilis,
            'publisher'          => $request->penerbit,
            'subtitle'           => $request->subtitle,
            'weight'             => $request->berat,
            'width'              => $request->panjang,
            'height'             => $request->lebar,
        );

        $book    = Book::create($create);
        $data    = array('text' => $request->sinopsis);
        $message = 'Berhasil menambah buku';

        $book->synopsis()->create($data);

        return redirect()->back()->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book, Request $request)
    {
        $ratings = $book->ratings();

        if ($request->filter && $request->filter != 'all') {
            $conditions = array(
                array('rating', $request->filter),
                array('book_id', $book->id),
            );

            $ratings = Rating::where($conditions);
        }

        $ratings = $ratings
            ->orderByDesc('created_at')
            ->paginate(10)
            ->fragment('rating');

        $data = compact('book', 'ratings');

        return view('book.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $book_categories = Category::get();
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
            'gambar_sampul_buku' => array('nullable', 'mimes:jpg,jpeg,png', 'max:2000'),
        );

        $request->validate($rules);

        $create = array('name' => $request->nama_penulis);
        $author = Author::firstWhere('name', $request->nama_penulis) ?? Author::create($create);
        $update = array(
            'isbn'               => $request->isbn,
            'category_id'        => $request->kategori,
            'printed_book_stock' => $request->jumlah_barang,
            'name'               => $request->judul_buku,
            'price'              => $request->price,
            'author_id'          => $author->id,
            'discount'           => $request->diskon ?? 0,
            'ebook'              => $request->tersedia_dalam_ebook,
            'pages'              => $request->jumlah_halaman,
            'release_date'       => $request->tanggal_rilis,
            'publisher'          => $request->penerbit,
            'subtitle'           => $request->subtitle,
            'weight'             => $request->berat,
            'width'              => $request->panjang,
            'height'             => $request->lebar,
        );

        $data = array('text' => $request->sinopsis);

        $book->update($update);
        $book->synopsis()->update($data);

        $message = 'Berhasil mengedit buku';

        return redirect()->back()->with('message', $message);
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

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function addStock(Request $request, Book $book)
    {
        $rules = array(
            'amount' => array('required', 'numeric'),
        );

        $request->validate($rules);

        $stock  = $book->printed_book_stock + $request->amount;
        $update = array('printed_book_stock' => $stock);

        $book->update($update);

        $message = 'Berhasil menambah ' . $request->amount . ' stok buku';

        return redirect()->back()->with('message', $message);
    }

    public function search(Request $request)
    {
        $value   = $request->keywords;
        $get     = array('id', 'name');
        $books   = Book::where('name', 'LIKE', "%$value%")->get($get);
        $authors = Author::where('name', 'LIKE', "%$value%")->get($get);
        $data    = array(
            'books' => $books,
            'authors' => $authors,
        );

        $response = array(
            'status' => 'success',
            'code' => 200,
            'data' => $data,
        );

        return response()->json($response);
    }

    public function getAuthors(Request $request) {
        $rules       = array(
            'author_name' => [
                'required',
                function ($attr, $value, $fail) {
                    $existsAuthors = fn () => Author::where('name', 'LIKE', "%$value%")->exists();

                    if (! $existsAuthors()) {
                        $fail($attr . ' tidak valid');
                    }
                }
            ],
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = array(
                'status'  => 'fail',
                'code'    => 400,
                'data'    => null ,
                'message' => $validator->errors(),
            );
        } else {
            $authors = Author::where('name', 'LIKE', "%$request->author_name%")->get();
            $data    = compact('authors');

            $response = array(
                'status' => 'success',
                'code' => 200,
                'data' => $data,
                'message' => 'Hasil pencarian berhasil',
            );
        }

        return response()->json($response);
    }

    public function addDiscount(Request $request, Book $book) {
        $price = $book->price - $book->discount;
        $rules = array(
            'amount' => 'required|numeric|lt:' . $price,
        );

        $request->validate($rules);

        $data    = array('discount' => $request->amount);
        $message = 'Berhasil menambah / update diskon buku';

        $book->update($data);

        return redirect()->back()->with('message', $message);
    }
}
