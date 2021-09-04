<?php

namespace App\Http\Controllers;

use App\Models\{User, Book, Checkout};
use Illuminate\Support\Facades\{Auth, Validator};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkout(Request $request)
    {
        $carts = $request->all()['carts'];
        $datas = collect($carts)->map(function ($cart) {
            $cart    = explode('-', $cart);
            $book    = Book::find($cart[1]);
            $results = array(
                'book_version' => $cart[0],
                'book'         => $book,
                'amount'       => $cart[2],
                'note'         => $cart[3] ?? null,
            );

            return $results;
        });

        $checkouts  = Checkout::where('user_id', Auth::id());

        $checkouts->delete();

        $datas = $datas->map(function ($data) {
            return array(
                'user_id'      => Auth::id(),
                'book_id'      => $data['book']->id,
                'book_version' => $data['book_version'] == 0 ? 'hard_cover' : 'ebook',
                'amount'       => $data['amount'],
                'note'         => $data['note'],
                "created_at"   => \Carbon\Carbon::now(),
                "updated_at"   => \Carbon\Carbon::now(),
            );
        })->toArray();

        Checkout::insert($datas);

        return redirect()->route('checkout.index');
    }

    public function index()
    {
        $checkouts = Auth::user()->checkouts;

        if ($checkouts->isEmpty()) {
            return abort(404);
        } else {
            $amount = $checkouts->map(fn ($checkout) => $checkout->amount);
            $amount = $amount->reduce(fn ($carry, $item) => $carry + $item);

            $total = $checkouts->map(function ($checkout) {
                $result = ($checkout->books[0]->price - $checkout->books[0]->discount) * $checkout->amount;

                return $result;
            });

            $total = $total->reduce(fn ($carry, $item) => $carry + $item);

            return view('checkout.index', compact('checkouts', 'amount', 'total'));
        }
    }

    // Melakukan checkout dan data di masukan ke tb book_user
    public function checkoutPayment(Request $request, User $user)
    {
        // Validasi
        $rules = array(
            'customer' => 'required|exists:customers,id',
            'courier_name' => array(
                'required',
                Rule::in(['jne', 'pos', 'tiki']),
            ),
            'courier_service' => 'required',
            'payment_method' => array(
                'required',
                Rule::in(['bri', 'bni', 'bca']),
            ),
        );

        $validator = Validator::make($request->all(), $rules, array(
            'customer.required' => 'Alamat pengiriman wajib diisi.',
            'courier_name.required' => 'Pilihan kurir wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib diisi.',
            'courier_service.required' => 'Layanan kurir wajib diisi.',
        ));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        // End Validasi

        $checkouts = $user->checkouts;
        $user = User::find(Auth::user());

        $data = array(
            'invoice' => substr(time(), 0, 10),
            'book_version' => '',
            'amount' => '',
            'courier_name' => '',
            'courier_service' => '',
            'shipping_cost' => '',
            'note' => '',
            'insurance' => '',
            'unique_code' => '',
            'total_payment' => '',
            'payment_method' => '',
            'payment_status' => '',
            'payment_deadline' => '',
        );

        foreach ($checkouts as $checkout) {
            dump($checkout->book_id);
        }

        dump($request->all());
    }
}
