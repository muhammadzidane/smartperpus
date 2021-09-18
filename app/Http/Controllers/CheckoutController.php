<?php

namespace App\Http\Controllers;

use App\Models\{User, Book, Checkout, Customer};
use Illuminate\Support\Facades\{Auth, Validator, Date, BookUser};
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
        // Validasi
        $rules = array(
            'carts'        => 'required',
        );

        $request->validate($rules);
        // End Validasi

        $carts = $request->all()['carts'];
        $datas = collect($carts)->map(function ($cart) {
            $cart    = explode('-', $cart);

            if (count($cart) > 3) {
                $note = collect($cart)
                    ->slice(2)
                    ->map(fn ($note) => $note == '' ? '-' : $note)
                    ->join('-');
            } else {
                $note = $cart[2] ?? null;
            }

            $book   = Book::find($cart[0]);
            $amount = $book->carts()->where('user_id', auth()->id())->first()->amount;

            $results = array(
                'book_version' => $cart[1],
                'book'         => $book,
                'amount'       => $amount,
                'note'         => $note,
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
        $checkouts = Checkout::where('user_id', Auth::id())->paginate(8);

        if ($checkouts->isEmpty()) {
            return abort(404);
        } else {
            $amount = $checkouts->map(fn ($checkout) => $checkout->amount);
            $amount = $amount->reduce(fn ($carry, $item) => $carry + $item);

            $total = $checkouts->map(function ($checkout) {
                $result = ($checkout->books[0]->price - $checkout->books[0]->discount) * $checkout->amount;

                return $result;
            });

            $user          = User::find(auth::id());
            $main_customer = $user->customers()->firstWhere('main', true);
            $total         = $total->reduce(fn ($carry, $item) => $carry + $item);
            $data          = compact('checkouts', 'amount', 'total', 'main_customer');

            return view('checkout.index', $data);
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

        $checkouts     = $user->checkouts;
        $random_digits = 3;
        $unique_code   = rand(pow(10, $random_digits - 1), pow(10, $random_digits) - 1);
        $invoice       = substr(time(), 0, 10);

        foreach ($checkouts as $checkout) {
            $book            = Book::find($checkout->book_id);
            $courier_service = explode('-', $request->courier_service)[1];
            $shipping_cost   = $request->shipping_cost;
            $total_payment   = ($book->price - $book->discount) * $checkout->amount;

            switch ($request->payment_method) {
                case 'bri':
                    $payment_method = 'Transfer Bank BRI';

                    break;
                case 'bni':
                    $payment_method = 'Transfer Bank BNI';

                    break;
                case 'bca':
                    $payment_method = 'Transfer Bank BCA';

                    break;
            }

            $data = array(
                'book_id'          => $checkout->book_id,
                'customer_id'      => $request->customer,
                'invoice'          => $invoice,
                'book_version'     => $checkout->book_version,
                'amount'           => $checkout->amount,
                'courier_name'     => $request->courier_name,
                'courier_service'  => $courier_service,
                'shipping_cost'    => $shipping_cost,
                'note'             => $checkout->note,
                'insurance'        => 0,
                'unique_code'      => $unique_code,
                'total_payment'    => $total_payment,
                'payment_method'   => $payment_method,
                'payment_status'   => 'waiting_for_confirmation',
                'payment_deadline' => Date::now()->addDays(1)->format('Y-m-d H:i:s'),
            );

            $reduce_book_stock = $book->printed_book_stock - $checkout->amount;
            $reduce_book_stock = array('printed_book_stock' => $reduce_book_stock);

            $book->users()->attach($user->id, $data);
        }

        return redirect()->route('book.purchases.show', array('invoice' => $invoice));
    }

    public function changeMainAddress(Request $request)
    {
        $customer = Customer::find($request->customer);

        if ($customer) {
            $user = User::find(auth()->user()->id);

            $data = array(
                'main' => false,
            );

            $user->customers()->update($data);

            $data = array(
                'main' => true,
            );

            $user->customers()->find($request->customer)->update($data);

            return redirect()->back()->with('message', 'Berhasil mengubah alamat utama');
        } else {
            return redirect()->back()->with('message', 'Alamat tidak ditemukan');
        }
    }
}
