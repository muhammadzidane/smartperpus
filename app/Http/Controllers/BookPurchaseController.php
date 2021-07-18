<?php

namespace App\Http\Controllers;

use App\Models\{Book, BookPurchase, BookUser, User};
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Date, Validator, DB};
use Faker\Factory as Faker;
use Illuminate\Http\Request;

class BookPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dump('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Book $book)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'pilihan_kurir'     => array('required'),
                'metode_pembayaran' => array('required'),
                'alamat_pengiriman' => array('required'),
            )
        );

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($request->ajax()) {
                return response()->json(compact('errors'));
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            $faker = Faker::create('id_ID');

            $data = array(
                'invoice'          => $faker->unique()->numerify('##########'),
                'book_version'     => $request->book_version,
                'amount'           => $request->amount,
                'courier_name'     => $request->courier_name,
                'courier_service'  => $request->pilihan_kurir,
                'shipping_cost'    => $request->shipping_cost,
                'note'             => $request->note,
                'insurance'        => $request->insurance,
                'unique_code'      => $request->unique_code,
                'total_payment'    => $request->total_pembayaran + $request->unique_code,
                'payment_method'   => $request->metode_pembayaran,
                'payment_deadline' => Date::now()->addDays(1)->format('Y-m-d H:i:s'),
                'payment_status'   => 'waiting_for_confirmation',
            );

            $user          = User::find(Auth::id());
            $book_user     = $book->users()->attach($user, $data);
            $book_user     = DB::table('book_user')->latest()->first();
            $url           = Route('book-purchases.show', array('book_user' => $book_user->id));

            if ($request->ajax()) {
                return response()->json(compact('book_user', 'url'));
            } else {
                return redirect()->route('book-purchases.show', array('book_user' => $book_user));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookPurchase  $bookPurchase
     * @return \Illuminate\Http\Response
     */
    public function show(BookUser $bookUser)
    {
        if ($bookUser->user_id == Auth::id()) {
            if ($bookUser->payment_status == 'failed') {
                return redirect()->route('home');
            } else {
                return view('book.book-payment', compact('bookUser'));
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookPurchase  $bookPurchase
     * @return \Illuminate\Http\Response
     */
    public function edit(BookUser $bookPurchase)
    {
        dump('wkwk');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookPurchase  $bookPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookPurchase $bookPurchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookPurchase  $bookPurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookUser $bookUser)
    {
        $now      = Carbon::now();
        $deadline = $bookUser->payment_deadline;
        $deadline = $now->diffAsCarbonInterval($deadline);
        $hours    = strlen($deadline->h) > 1 ? $deadline->h : str_pad($deadline->h, 2, '0', STR_PAD_LEFT);
        $minutes  = strlen($deadline->i) > 1 ? $deadline->i : str_pad($deadline->i, 2, '0', STR_PAD_LEFT);
        $seconds  = strlen($deadline->s) > 1 ? $deadline->s : str_pad($deadline->s, 2, '0', STR_PAD_LEFT);
        $deadline = $deadline->h . ' : ' . $minutes . ' : ' . $seconds;

        $delete = $bookUser->delete();
        $url    = route('home');

        return response()->json(compact('delete', 'url'));
    }

    public function ajaxPaymentDeadline()
    {
        foreach (BookUser::get() as $bookUser) {
            $now           = Carbon::now();
            $deadline      = $bookUser->payment_deadline;

            if ($now->greaterThan($deadline)) {
                $update     = array('payment_status' => 'failed');

                $bookUser->update($update);
            }
        }

        return response()->json()->status();
    }

    public function ajaxPaymentDeadlineText(BookUser $bookUser)
    {
        $now      = Carbon::now();
        $deadline = $bookUser->payment_deadline;
        $deadline = $now->diffAsCarbonInterval($deadline);
        $hours    = (string) strlen($deadline->h) > 1 ? $deadline->h : str_pad($deadline->h, 2, '0', STR_PAD_LEFT);
        $minutes  = (string) strlen($deadline->i) > 1 ? $deadline->i : str_pad($deadline->i, 2, '0', STR_PAD_LEFT);
        $seconds  = (string) strlen($deadline->s) > 1 ? $deadline->s : str_pad($deadline->s, 2, '0', STR_PAD_LEFT);
        $deadline = $hours . ' : ' . $minutes . ' : ' . $seconds;
        $home     = route('home');

        return response()->json(compact('deadline', 'home', 'hours', 'minutes', 'seconds'));
    }
}
