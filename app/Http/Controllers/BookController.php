<?php

namespace App\Http\Controllers;

use App\Models\{Book, Author, Category, BookImage};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Validator, Auth, File};

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
        // Filter
        $filters = array(
            'category_id' => $request->category,
            'min_price'   => $request->min_price,
            'max_price'   => $request->max_price,
        );

        $filters = collect($filters);

        $check_empty_filters = $filters->every(function ($filter) {
            return empty($filter);
        });

        $between    = [(int) $request->min_price, (int) $request->max_price == 0 ? 99999999 : $request->max_price];
        $books      = $request->category
            ? Book::where('name',   'LIKE', "%$request->keywords%")->whereIn('category_id', $request->category)
            : Book::where('name',   'LIKE', "%$request->keywords%");

        switch ($request->sort) {
            case 'relevan':
                $authors = $books
                    ->whereBetween('price', $between)
                    ->get();;
                $authors = $authors->map(function ($book) {
                    return $book->author;
                });

                $books = $books->whereBetween('price', $between)
                    ->paginate(50)
                    ->withQueryString();

                break;
            case 'lowest-rating':
                $authors = $books
                    ->orderBy('rating')
                    ->whereBetween('price', $between)
                    ->get();

                $authors = $authors->map(function ($book) {
                    return $book->author;
                });

                $books = $books->orderBy('rating')->whereBetween('price', $between)
                    ->paginate(50)
                    ->withQueryString();
                break;
            case 'highest-rating':
                $authors = $books
                    ->orderByDesc(
                        'rating'
                    )->whereBetween('price', $between)
                    ->get();

                $authors = $authors->map(function ($book) {
                    return $book->author;
                });

                $books = $books->orderByDesc('rating')->whereBetween('price', $between)
                    ->paginate(50)
                    ->withQueryString();
                break;
            case 'lowest-price':
                $authors = $books
                    ->orderBy('price')
                    ->whereBetween('price', $between)
                    ->get();

                $authors = $authors->map(function ($book) {
                    return $book->author;
                });

                $books = $books
                    ->orderBy('price')
                    ->whereBetween('price', $between)
                    ->paginate(50)
                    ->withQueryString();
                break;
            case 'highest-price':
                $authors = $books
                    ->orderByDesc('price')
                    ->whereBetween('price', $between)
                    ->get();

                $authors = $authors->map(function ($book) {
                    return $book->author;
                });

                $books = $books
                    ->orderByDesc('price')
                    ->whereBetween('price', $between)
                    ->paginate(50)
                    ->withQueryString();
                break;

            default:
                $authors = $books->get();

                $authors = $authors->map(function ($book) {
                    return $book->author;
                });

                $books = $books
                    ->paginate(50)
                    ->withQueryString();
                break;
        }

        $authors = $authors->unique();
        // End Filter

        $keywords   = $request->keywords;
        $categories = Book::where('name', 'LIKE', "%$request->keywords%")->get();
        $categories = $categories->map(function ($book) {
            global $request;

            $between    = [(int) $request->min_price, (int) $request->max_price == 0 ? 99999999 : $request->max_price];

            $book_count = Book::where('name', 'LIKE', "%$request->keywords%")
                ->where('category_id', $book->category->id)
                ->whereBetween('price', $between)
                ->get()
                ->count();

            $results = array(
                'id'         => $book->category->id,
                'name'       => $book->category->name,
                'book_count' => $book_count,
            );

            return $results;
        });

        $categories = $categories->unique();
        session()->flashInput($request->input());

        return view('book.index', compact('books', 'authors', 'keywords', 'categories'));
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

        $book_image_name = $request->gambar_sampul_buku->getClientOriginalName();
        $validator       = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(compact('errors'));
        } else {
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

            $book = Book::create($create);
            $data = array('text' => $request->sinopsis);

            $book->synopsis()->create($data);

            return response()->json()->status();
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
            'gambar_sampul_buku' => array('nullable', 'mimes:png,jpg,jpeg', 'max:2000'),
        );

        $book_image_name = $request->gambar_sampul_buku != null ? $request->gambar_sampul_buku->getClientOriginalName() : $book->image;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(compact('errors'));
        } else {
            if ($request->gambar_sampul_buku) {
                $filename = 'storage/books/' . $book->image;
                File::delete($filename);
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

            $book->update($update);
            $data = array('text' => $request->sinopsis);

            $book->synopsis()->update($data);

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

    public function search(Request $request)
    {
        $value = $request->keywords;
        $get   = array('id', 'name');
        $books = Book::where('name', 'LIKE', "%$value%")->get($get)->take(10);

        return response()->json(compact('books'));
    }

    public function addBookImages(Request $request, Book $book)
    {
        $rules = array(
            'image' => 'required|mimes:png,jpg,jpeg|max:2000',
        );

        $validator = Validator::make($request->all(), $rules);

        $request->validate($rules);

        if (!$validator->fails()) {
            $user   = Auth::user();
            $time   = time();
            $image  = $user->first_name . '-' . $user->last_name . '-' . $user->email . '-' . $time;
            $image  = '.' . $request->image->getClientOriginalExtension();
            $create = array('image' => $image);
            $book->book_images()->create($create);

            $request->image->storeAs('public/books/book_images', $image);

            return response()->json(compact('image'));
        } else {
            $errors = $validator->errors();

            return response()->json(compact('errors'));
        }
    }
}
