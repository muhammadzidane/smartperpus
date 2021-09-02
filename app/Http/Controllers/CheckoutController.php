<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $carts = $request->all()['carts'];
        $datas = collect($carts)->map(function ($cart) {
            $cart    = explode('-', $cart);
            $book    = Book::find($cart[1]);
            $results = array(
                'book'    => $book,
                'amount'  => $cart[2],
                'note'    => $cart[3] ?? null,
            );

            return $results;
        });

        return view('checkout.index', compact('datas'));
    }
}
