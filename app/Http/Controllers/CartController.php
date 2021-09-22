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
        $selected_value = array(
            'books.*',
            'carts.amount as cart_amount',
        );

        $books = Book::join('carts', 'books.id', '=', 'carts.book_id')
            ->join('users', 'carts.user_id', '=', 'users.id')
            ->orderByDesc('created_at')
            ->select($selected_value)
            ->where('users.id', auth()->user()->id)
            ->get();

        $book_id_session = session('bought_directly');

        if ($book_id_session) { // Membuat session untuk checked fitur beli langsung, dan di taruh paling atas
            $book_session = Book::find($book_id_session);

            $books = $books->filter(function ($book, $key) {
                return $book->id != session('bought_directly');
            });

            $books->prepend($book_session);
        }

        if (session('buy_again')) {
            $sliced_books = $books->whereIn('id', session('buy_again'));
            $filter_books = $books->filter(function($book) {
                return in_array($book->id, session('buy_again')) == false;
            });

            $filter_books->all();

            $books = $sliced_books->merge($filter_books);

            $books->all();

            $books->map(function ($book) {
                $book['buy_again'] = session('buy_again');

                return $book;
            });

            $total_payment = $sliced_books->map(function($book) {
                $result = ($book->price - $book->discount) * $book->cart_amount;

                return $result;
            });


            $amount        = $sliced_books->reduce(fn ($carry, $item) => $carry + $item->cart_amount);
            $total_payment = $total_payment->reduce(fn ($carry, $item) => $carry + $item);
        }

        $data_perpage = 8;
        $slice_book   = $request->page == 1 || $request->page == null ? 0 : $request->page * $data_perpage - $data_perpage;

        $books = new LengthAwarePaginator($books->slice($slice_book, $data_perpage), $books->count(), $data_perpage, $request->page, [
            'path' => url()->current(),
            'pageName' => 'page',
        ]);

        $total_payment = isset($total_payment) ? number_format($total_payment, 0, 0, '.') : null;
        $amount        = isset($amount) ? $amount : null;
        $data          = compact('books', 'total_payment', 'amount');

        return view('cart.index', $data);
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

        // Buat cart baru / Beli Langsung
        if ($carts->count() == 0) {
            $data = array(
                'user_id' => auth()->user()->id,
                'book_id' => $book->id,
                'amount'  => 1,
            );

            $cart            = Cart::create($data);
            $bought_directly = $cart->books()->first()->id;
            $total_payment   = ($book->price - $book->discount) * $cart->amount;
        } else {
            $bought_directly = $book->id;
            $cart            = $book->carts()->firstWhere('user_id', auth()->user()->id);
            $total_payment   = ($book->price - $book->discount) * $cart->amount;
        }

        $data = array(
            'bought_directly' => $bought_directly,
            'amount'          => $cart->amount,
            'total_payment'   => number_format($total_payment, 0, 0, '.'),
        );

        return redirect()->route('carts.index')->with($data);
    }
}
