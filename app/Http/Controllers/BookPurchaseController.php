<?php

namespace App\Http\Controllers;

use App\Models\{Book, BookPurchase, BookUser, User, Customer};
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Date, Validator, DB, Storage};
use Faker\Factory as Faker;
use Illuminate\Http\Request;

class BookPurchaseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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

            $book_stock = $book->printed_book_stock;
            $stock      = $book - $request->amount;
            $data       = array('printed_book_stock' => $stock);
            $book->update($data);

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
    public function show($invoice, Request $request)
    {
        $user       = User::find(auth()->user()->id);
        $conditions = array(
            array('user_id', $user->id),
            array('invoice', $invoice),
        );

        $book_users             = BookUser::where($conditions)->get();
        $total_payment          = $book_users->sum('total_payment');
        $total_payment          = $total_payment + $book_users[0]->unique_code + +$book_users[0]->shipping_cost;
        $total_payment_sub_last = substr($total_payment, -3);
        $total_payment_sub      = substr($total_payment, 0, -3);
        $total_payment          = substr(rupiah_format($total_payment_sub . $total_payment_sub_last), 0, -3);
        $first_book_user        = $book_users->first();
        $courier_name           = $book_users->first()->courier_name;
        $customer               = Customer::find($first_book_user->customer_id);
        $now                    = Carbon::now();
        $deadline               = $first_book_user->payment_deadline;
        $deadline_has_reached   = $now->greaterThan($deadline);

        if ($deadline_has_reached) {
            $hours   = '00';
            $minutes = '00';
            $seconds = '00';
        } else {
            $diff_deadline = $now->diffAsCarbonInterval($deadline);
            $hours         = strlen($diff_deadline->h) > 1 ? $diff_deadline->h : str_pad($diff_deadline->h, 2, '0', STR_PAD_LEFT);
            $minutes       = strlen($diff_deadline->i) > 1 ? $diff_deadline->i : str_pad($diff_deadline->i, 2, '0', STR_PAD_LEFT);
            $seconds       = strlen($diff_deadline->s) > 1 ? $diff_deadline->s : str_pad($diff_deadline->s, 2, '0', STR_PAD_LEFT);
        }

        $deadline_time = compact('hours', 'minutes', 'seconds');

        // redirect -> '/status/unpaid' . Jika bukti pembayaran sudah di proses
        if ($first_book_user->upload_payment_image != null) {
            return redirect('/status/unpaid#' . $first_book_user->invoice);
        }

        switch ($courier_name) {
            case 'jne':
                $courier_name = 'Jalur Nugraha Ekakurir (JNE)';
                break;
            case 'pos':
                $courier_name = 'POS Indonesia';
                break;
            case 'tiki':
                $courier_name = 'Citra Van Titipan Kilat (TIKI)';
                break;
            default:
                $courier_name = '';
                break;
        }

        switch ($first_book_user->payment_method) {
            case 'Transfer Bank BNI':
                $atm = array(
                    'lists' => array(
                        'Pilih “MENU LAIN”',
                        'Pilih “TRANSFER”',
                        'Pilih “TRANSAKSI LAINNYA”',
                        'Pilih ke “REKENING BNI”',
                        'Masukkan Nomor Account Virtual, lalu tekan “BENAR”',
                        'Masukkan NOMINAL, lalu tekan “YA”',
                        'Konfirmasi transaksi selesai, tekan “TIDAK” untuk menyelesaikan transaksi',
                    ),
                    'title' => 'BNI - ATM'
                );

                $internet_banking = array(
                    'lists' => array(
                        'Pilih “TRANSAKSI”',
                        'Pilih “INFO & ADMINISTRASI TRANSFER”',
                        'Pilih “ATUR REKENING TUJUAN”',
                        'Tambahkan rekening tujuan Kemudian klik “OK”',
                        'Isi data rekening dan tekan “LANJUTKAN”',
                        'Rincian konfirmasi akan muncul, jika benar dan sesuai, ukkan 8-digit angka yang dihasilkan dari APPLI 2 pada token BNI Anda lalu klik “PROSES”',
                        'Akun tujuan berhasil ditambahkan',
                        'Pilih “TRANSFER”',
                        'Pilih “TRANSFER KE REKENING BNI”, lalu lengkapi semua data rekening penerima, lalu klik "LANJUTKAN"',
                        'Transaksi Anda telah berhasil',
                    ),
                    'title' => 'BNI - Internet Banking'
                );

                $mobile_banking = array(
                    'lists' => array(
                        'Log in BNI Mobile Banking (unduh versi terbaru)',
                        'Pilih menu “TRANSFER”',
                        'Pilih “SESAMA BANK BNI”',
                        'Isi kolom “REKENING DEBET” lalu klik menu “KE REKENING',
                        'Lengkapi data dengan mengisi, NAMA, NO VIRTUAL ACCOUNT DAN ALAMAT EMAIL BENEFICIARY',
                        'Konfirmasi akan muncul kemudian klik “LANJUTKAN”',
                        'Isi semua form yang ada lalu klik “LANJUTKAN”',
                        'Rincian konfirmasi muncul dengan meminta password transaksi, setelah selesai klik “LANJUTKAN”',
                        'Transaksi Anda telah berhasil',
                    ),
                    'title' => 'BNI - Mobile Banking'

                );

                $payment_instructions = compact('atm', 'internet_banking', 'mobile_banking');
                break;
            case 'Transfer Bank BRI':
                $atm = array(
                    'lists' => array(
                        'Pilih “MENU LAIN”',
                        'Pilih “TRANSFER”',
                        'Pilih “TRANSAKSI LAINNYA”',
                        'Pilih ke “REKENING BRI”',
                        'Masukkan Nomor Account Virtual, lalu tekan “BENAR”',
                        'Masukkan NOMINAL, lalu tekan “YA”',
                        'Konfirmasi transaksi selesai, tekan “TIDAK” untuk menyelesaikan transaksi',
                    ),
                    'title' => 'BRI - ATM'
                );

                $internet_banking = array(
                    'lists' => array(
                        'Pilih “TRANSAKSI”',
                        'Pilih “INFO & ADMINISTRASI TRANSFER”',
                        'Pilih “ATUR REKENING TUJUAN”',
                        'Tambahkan rekening tujuan Kemudian klik “OK”',
                        'Isi data rekening dan tekan “LANJUTKAN”',
                        'Rincian konfirmasi akan muncul, jika benar dan sesuai, ukkan 8-digit angka yang dihasilkan dari APPLI 2 pada token BRI Anda lalu klik “PROSES”',
                        'Akun tujuan berhasil ditambahkan',
                        'Pilih “TRANSFER”',
                        'Pilih “TRANSFER KE REKENING BRI”, lalu lengkapi semua data rekening penerima, lalu klik "LANJUTKAN"',
                        'Transaksi Anda telah berhasil',
                    ),
                    'title' => 'BRI - Internet Banking'
                );

                $mobile_banking = array(
                    'lists' => array(
                        'Log in BRI Mobile Banking (unduh versi terbaru)',
                        'Pilih menu “TRANSFER”',
                        'Pilih “SESAMA BANK BRI”',
                        'Isi kolom “REKENING DEBET” lalu klik menu “KE REKENING',
                        'Lengkapi data dengan mengisi, NAMA, NO VIRTUAL ACCOUNT DAN ALAMAT EMAIL BENEFICIARY',
                        'Konfirmasi akan muncul kemudian klik “LANJUTKAN”',
                        'Isi semua form yang ada lalu klik “LANJUTKAN”',
                        'Rincian konfirmasi muncul dengan meminta password transaksi, setelah selesai klik “LANJUTKAN”',
                        'Transaksi Anda telah berhasil',
                    ),
                    'title' => 'BRI - Mobile Banking'

                );

                $payment_instructions = compact('atm', 'internet_banking', 'mobile_banking');
                break;
            case 'Transfer Bank BCA':
                $atm = array(
                    'lists' => array(
                        'Pilih “MENU LAIN”',
                        'Pilih “TRANSFER”',
                        'Pilih “TRANSAKSI LAINNYA”',
                        'Pilih ke “REKENING BCA”',
                        'Masukkan Nomor Account Virtual, lalu tekan “BENAR”',
                        'Masukkan NOMINAL, lalu tekan “YA”',
                        'Konfirmasi transaksi selesai, tekan “TIDAK” untuk menyelesaikan transaksi',
                    ),
                    'title' => 'BCA - ATM'
                );

                $internet_banking = array(
                    'lists' => array(
                        'Pilih “TRANSAKSI”',
                        'Pilih “INFO & ADMINISTRASI TRANSFER”',
                        'Pilih “ATUR REKENING TUJUAN”',
                        'Tambahkan rekening tujuan Kemudian klik “OK”',
                        'Isi data rekening dan tekan “LANJUTKAN”',
                        'Rincian konfirmasi akan muncul, jika benar dan sesuai, ukkan 8-digit angka yang dihasilkan dari APPLI 2 pada token BCA Anda lalu klik “PROSES”',
                        'Akun tujuan berhasil ditambahkan',
                        'Pilih “TRANSFER”',
                        'Pilih “TRANSFER KE REKENING BCA”, lalu lengkapi semua data rekening penerima, lalu klik "LANJUTKAN"',
                        'Transaksi Anda telah berhasil',
                    ),
                    'title' => 'BCA - Internet Banking'
                );

                $mobile_banking = array(
                    'lists' => array(
                        'Log in BCA Mobile Banking (unduh versi terbaru)',
                        'Pilih menu “TRANSFER”',
                        'Pilih “SESAMA BANK BCA”',
                        'Isi kolom “REKENING DEBET” lalu klik menu “KE REKENING',
                        'Lengkapi data dengan mengisi, NAMA, NO VIRTUAL ACCOUNT DAN ALAMAT EMAIL BENEFICIARY',
                        'Konfirmasi akan muncul kemudian klik “LANJUTKAN”',
                        'Isi semua form yang ada lalu klik “LANJUTKAN”',
                        'Rincian konfirmasi muncul dengan meminta password transaksi, setelah selesai klik “LANJUTKAN”',
                        'Transaksi Anda telah berhasil',
                    ),
                    'title' => 'BCA - Mobile Banking'

                );

                $payment_instructions = compact('atm', 'internet_banking', 'mobile_banking');
                break;

            default:
                $payment_instructions = array();
                break;
        }

        $data = compact(
            'first_book_user',
            'customer',
            'total_payment',
            'total_payment_sub_last',
            'courier_name',
            'deadline_time',
            'deadline_has_reached',
            'payment_instructions',
        );

        return view('book.book-payment', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookPurchase  $bookPurchase
     * @return \Illuminate\Http\Response
     */
    public function edit(BookUser $bookPurchase)
    {
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

    public function uploadPayment(Request $request, $invoice)
    {
        $rules = array(
            'upload_payment' => array('required', 'mimes:jpg,png,jpeg', 'max:2000')
        );

        $request->validate($rules);

        $user          = Auth::user();
        $path_store    = "$user->first_name-$user->last_name-$user->email-";
        $path_store   .= time() . '.' . $request->upload_payment->getClientOriginalExtension();
        $update        = array(
            'upload_payment_image' => $path_store,
            'failed_message' => null,
        );

        $book_users = BookUser::where('invoice', $invoice);
        $book_users->update($update);

        if ($request->upload_payment && !Storage::exists('public/uploaded_payment/' . $path_store)) {
            $request->upload_payment->storeAs('public/uploaded_payment', $path_store);
        }

        return redirect()->back()->with('message', 'Berhasil mengupload bukti pembayaran');
    }

    public function ajaxPaymentDeadline()
    {
        foreach (BookUser::get() as $bookUser) {
            $now           = Carbon::now();
            $deadline      = $bookUser->payment_deadline;

            if ($now->greaterThan($deadline) && $bookUser->payment_status == 'waiting_for_confirmation' && $bookUser->confirmed_payment == 0) {
                $update     = array(
                    'payment_status' => 'failed',
                    'failed_message' => 'Dibatalkan secara otomatis oleh sistem kami',
                    'failed_date'    => $now->format('Y-m-d H:i:s'),
                );

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
