<?php

namespace App\Http\Controllers;

use App\Models\{Cart, Book, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user  = User::find(auth()->user()->id);
        $carts = $user->carts->sortByDesc('created_at');

        $books = $carts->map(function ($cart) {
            return Book::find($cart->book_id);
        });

        $data_perpage = 8;
        $slice_book   = $request->page == 1 || $request->page == null ? 0 : $request->page * $data_perpage - $data_perpage;

        $book_id_session = session('bought_directly');

        if ($book_id_session) { // Membuat session untuk checked buku pilihan, dan di taruh paling atas
            $cart_session = $user->carts()->firstWhere('book_id', $book_id_session);
            $book_session = Book::find($cart_session->book_id);

            $books = $books->filter(function ($book, $key) {
                return $book->id != session('bought_directly');
            });

            $books->prepend($book_session);
        }

        $books = new LengthAwarePaginator($books->slice($slice_book, $data_perpage), $books->count(), $data_perpage, $request->page, [
            'path' => url()->current(),
            'pageName' => 'page',
        ]);

        return view('cart.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datas = $request->except('_token');
        $cart  = Cart::create($datas);

        return response()->json(compact('cart'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        $cart->update($request->except('_token', '_method'));

        $response = array(
            'status' => 'success',
            'code' => 200,
            'data' => null,
        );

        return response()->json($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $delete = $cart->delete();

        return response()->json(compact('delete'));
    }

    public function boughtDirectly(Book $book)
    {
        $conditions = array(
            array('user_id', auth()->user()->id),
            array('book_id', $book->id)
        );

        $carts = Cart::where($conditions)->get();

        // Buat cart baru
        if ($carts->count() == 0) {
            $data = array(
                'user_id' => auth()->user()->id,
                'book_id' => $book->id,
            );

            $cart = Cart::create($data);

            return redirect()->route('carts.index')->with('bought_directly', $cart->book_id);
        } else {
            return redirect()->route('carts.index')->with('bought_directly', $book->id);
        }
    }
}
