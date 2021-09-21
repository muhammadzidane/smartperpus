<?php

namespace App\Http\Controllers;

use App\Models\{User, Book, BookUser, Customer, Cart, City, Rating};
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        switch ($request->path()) {
                // Guest
            case 'status/all':
                $status_title = 'Semua';
                break;
            case 'status/failed':
                $payment_status = 'failed';
                $status_title   = 'Dibatalkan';
                break;
            case 'status/unpaid':
                $payment_status = 'waiting_for_confirmation';
                $status_title   = 'Belum Dibayar';
                break;
            case 'status/on-process':
                $payment_status = 'order_in_process';
                $status_title   = 'Sedang Diproses';
                break;
            case 'status/on-delivery':
                $payment_status = 'being_shipped';
                $status_title   = 'Sedang Dikirim';
                break;
            case 'status/completed':
                $payment_status = 'arrived';
                $status_title   = 'Selesai';
                break;

                // Admin
            case 'status/uploaded-payment':
                $payment_status = 'waiting_for_confirmation';
                $status_title   = 'Unggahan Bukti Pembayaran';
                break;
            default:

                break;
        }

        $user   = User::find(Auth::id());

        if (auth()->user()->role == 'guest') {
            $count = fn ($condition) => $user->books()->distinct('invoice')->where('payment_status', $condition)->count();

            $counts = array(
                'waiting_for_confirmation' => $count('waiting_for_confirmation'),
                'order_in_process'         => $count('order_in_process'),
                'being_shipped'            => $count('being_shipped'),
            );

            if ($request->path() != 'status/all') {
                $conditions = array(
                    array('book_user.user_id', $user->id),
                    array('book_user.payment_status', $payment_status)
                );
            } else {
                $conditions = array(
                    array('book_user.user_id', $user->id)
                );
            }
        } else { // Admin / Super Admin
            $counts = BookUser::get()->unique('invoice');
            $count  = fn ($conditions, $value = null) => $counts->where($conditions, $value)->count();

            $uploaded_payment_count = $counts
                ->where('payment_status', 'waiting_for_confirmation')
                ->where('upload_payment_image', '!=', null)
                ->count();

            $unpaid_count = $counts
                ->where('payment_status', 'waiting_for_confirmation')
                ->where('upload_payment_image', '==', null)
                ->count();

            $counts = array(
                'waiting_for_confirmation' => $unpaid_count,
                'order_in_process'         => $count('payment_status', 'order_in_process'),
                'being_shipped'            => $count('payment_status', 'being_shipped'),
                'uploaded_payment'         => $uploaded_payment_count,
            );

            if ($request->path() != 'status/all') {
                $conditions = array(
                    array('book_user.payment_status', $payment_status),
                );

                if ($request->path() == 'status/uploaded-payment') {
                    // Unggahan bukti pembayaran
                    $conditions = array(
                        array('book_user.payment_status', $payment_status),
                        array('book_user.upload_payment_image', '!=', null),
                    );
                } else if ($request->path() == 'status/unpaid') {
                    // Belum dibayar dan tidak ada bukti unggahan pembayaran
                    $conditions = array(
                        array('book_user.payment_status', $payment_status),
                        array('book_user.upload_payment_image', null),
                    );
                }
            } else {
                $conditions = array();
            }
        }

        $book_users = BookUser::where($conditions)
            ->orderBy('created_at', 'DESC')
            ->get();

        if ($request->status_keywords) {
            array_push(
                $conditions,
                array('books.name', 'LIKE', "%$request->status_keywords%"),
                array('book_user.invoice', 'LIKE', "%$request->status_keywords%", 'OR'),
                array('authors.name', 'LIKE', "%$request->status_keywords%", 'OR'),
            );

            $book_users = BookUser::join('books', 'book_user.book_id', '=', 'books.id')
                ->join('authors', 'books.author_id', '=', 'authors.id')
                ->select('book_user.*')
                ->where($conditions)
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        $book_users    = $book_users->unique('invoice');

        $book_users = $book_users->map(function ($book_user) {
            $book_user_invoice = BookUser::where('invoice', $book_user->invoice);

            $first_data    = $book_user_invoice->get()->first();
            $total_payment = $book_user_invoice->get()->sum('total_payment') + $first_data->unique_code + $first_data->shipping_cost;
            $amount        = $book_user_invoice->get()->sum('amount');

            $select_value = array(
                'books.id',
                'books.author_id',
                'books.name',
                'books.image',
                'books.price',
                'books.discount',
                'books.ebook',
                'book_user.amount',
                'book_user.book_version',
                'book_user.note',
                'ratings.review',
                'ratings.rating as book_rating',
            );

            if (auth()->user()->role == 'guest') {
                $conditions = array(
                    array('users.id', auth()->user()->id),
                    array('book_user.invoice', $book_user->invoice),
                );

                $books = Book::join('book_user', 'books.id', '=', 'book_user.book_id')
                    ->join('users', 'book_user.user_id', '=', 'users.id')
                    ->leftJoin('ratings', function ($join) {
                        $join->on('book_user.invoice', '=', 'ratings.invoice');
                        $join->on('book_user.book_id', '=', 'ratings.book_id');
                    })
                    ->select($select_value)
                    ->where($conditions)
                    ->get();
            } else {
                $books = Book::join('book_user', 'books.id', '=', 'book_user.book_id')
                    ->leftJoin('ratings', 'book_user.invoice', '=', 'ratings.invoice')
                    ->select($select_value)
                    ->where('book_user.invoice', $book_user->invoice)
                    ->get();
            }

            switch ($first_data->payment_status) {
                case 'failed':
                    $status = 'DIBATALKAN';
                    break;
                case 'waiting_for_confirmation':
                    $status = $first_data->upload_payment_image == null || request()->path() == 'status/unpaid'
                        ? 'BELUM DIBAYAR'
                        : 'BUKTI PEMBAYARAN';
                    break;
                case 'order_in_process':
                    $status = 'SEDANG DIPROSES';
                    break;
                case 'being_shipped':
                    $status = 'SEDANG DIKIRIM';
                    break;
                case 'arrived':
                    $status = 'SELESAI';
                    break;
            }

            return array(
                'first'         => $first_data,
                'status'        => $status,
                'user_fullname' => User::find($first_data->user_id)->first_name . ' ' . User::find($first_data->user_id)->last_name,
                'books'         => $books,
                'amount'        => $amount,
                'total_payment' => $total_payment,
            );
        });

        $data_perpage = 10;
        $slice_book   = $request->page == 1 || $request->page == null ? 0 : $request->page * $data_perpage - $data_perpage;
        $book_users   = new LengthAwarePaginator(
            $book_users->slice($slice_book, $data_perpage),
            $book_users->count(),
            $data_perpage,
            $request->page,
            [
                'path' => url()->current(),
                'pageName' => 'page',
            ]
        );

        $data = compact('book_users', 'status_title', 'counts');

        return view('status.index', $data);
    }

    public function detail($invoice)
    {
        $book_user     = BookUser::firstWhere('invoice', $invoice);

        if ($book_user->exists()) {
            $customer      = Customer::find($book_user->customer_id);
            $total_payment = BookUser::where('invoice', $invoice)->sum('total_payment');
            $total_payment = $total_payment + $book_user->unique_code + $book_user->shipping_cost;

            // Tanggal Status
            $order_date     = $book_user->created_at ? $book_user->created_at->format('Y-m-d H:i:s') : null;
            $payment_date   = $book_user->payment_date ? $book_user->payment_date->format('Y-m-d H:i:s') : null;
            $shipped_date   = $book_user->shipped_date ? $book_user->shipped_date->format('Y-m-d H:i:s') : null;
            $completed_date = $book_user->completed_date ? $book_user->completed_date->format('Y-m-d H:i:s') : null;
            $failed_date    = $book_user->failed_date ? $book_user->failed_date->format('Y-m-d H:i:s') : null;

            $status_date = compact('order_date', 'failed_date', 'payment_date', 'shipped_date', 'completed_date');

            $data = array(
                'book_user'     => $book_user,
                'customer'      => $customer,
                'district'      => $customer->district->name,
                'city'          => $customer->city->name,
                'city_type'     => $customer->city->type,
                'province'      => $customer->province->name,
                'total_payment' => $total_payment,
                'order_date'    => $order_date,
                'payment_date'  => $payment_date,
                'shipped_date'  => $shipped_date,
                'completed_date' => $completed_date,
                'failed_date'   => $failed_date,
                'status_date'   => $status_date,
            );

            $response = array(
                'status' => 'success',
                'code'   => 200,
                'data'   => $data,
            );
        } else {
            $response = array(
                'status' => 'fail',
                'code'   => 400,
                'data'   => null,
                'message' => 'Invoice tidak valid',
            );
        }

        return response()->json($response);
    }

    public function update($invoice, Request $request)
    {
        $nowTimeStampFormat = Carbon::now()->format('Y-m-d H:i:s');

        switch ($request->update) {
            case 'status-cancel-upload':
                $failed_message  = 'Dibatalkan oleh admin kami, karena anda mengirim bukti pembayaran yang kurang jelas';
                $failed_message .= '. Harap kirim lagi dengan benar';
                $message         = 'Unggahan bukti pembayaran berhasil dibatalkan';

                $data = array(
                    'upload_payment_image' => null,
                    'failed_message'       => $failed_message,
                    'failed_date'          => $nowTimeStampFormat,
                );
                break;

            case 'status-confirm-payment':
                $message      = 'Berhasil menkonfirmasi bukti transfer, dan akan segera diproses';

                $data = array(
                    'payment_status'    => 'order_in_process',
                    'confirmed_payment' => true,
                    'payment_date'      => $nowTimeStampFormat,
                    'failed_date'       => null,
                    'payment_deadline'  => null,
                );
                break;
            case 'status-on-delivery':
                $message = 'Barang selesai proses dan sedang dikirim oleh pihak ekspedisi';

                $data = array(
                    'payment_status' => 'being_shipped',
                    'resi_number'    => $request->resi_number,
                    'shipped_date'   => $nowTimeStampFormat,
                    'failed_date'    => null,
                );
                break;
            case 'status-complete':
                $message = 'Barang telah sampai ditujuan';

                $data = array(
                    'payment_status' => 'arrived',
                    'completed_date' => $nowTimeStampFormat,
                    'failed_date'    => null,
                );
                break;
        }

        $status_exists = BookUser::where('invoice', $invoice)->exists();

        if ($status_exists) {
            BookUser::where('invoice', $invoice)->update($data);

            // Mengurangi 1 stok buku cetak
            if ($request->update == 'status-complete') {
                $data = array(
                    'printed_book_stock' => DB::raw('books.printed_book_stock - 1'),
                );

                Book::join('book_user', 'books.id', '=', 'book_user.book_id')
                    ->select('books.*')
                    ->where('book_user.invoice', $invoice)
                    ->update($data);
            }

            $response = array(
                'status'  => 'success',
                'code'    => 200,
                'data'    => null,
                'message' => $message,
            );
        } else {
            $response = array(
                'status'  => 'fail',
                'code'    => 400,
                'data'    => null,
                'message' => 'Bad request, harap isi invoice dengan benar',
            );
        }

        return response()->json($response);
    }

    public function buyAgain(Request $request)
    {
        // Menghapus data yang ada, lalu menginsert kembali
        $conditions = array(
            array('users.id', auth()->user()->id),
            array('book_user.invoice', $request->invoice),
        );

        $carts = Cart::join('book_user', 'carts.book_id', '=', 'book_user.book_id')
            ->join('users', 'users.id', '=', 'carts.user_id')
            ->select('carts.*')
            ->where($conditions);

        $carts->delete();

        $get  = array(
            'user_id',
            'book_id',
            'amount',
        );

        $book_user = BookUser::where('invoice', $request->invoice)->get($get);
        $data      = $book_user->toArray();

        $data      = array_map(function ($value) {
            $now_format = date('Y-m-d H:i:s');

            $value['created_at'] = $now_format;
            $value['updated_at'] = $now_format;

            return $value;
        }, $data);

        $message = 'Berhasil menambah barang pada keranjang';

        Cart::insert($data);

        $flash = array(
            'message' => $message,
            'buy_again' => true,
        );

        return redirect()->route('carts.index')->with($flash);
    }

    public function addRating(Request $request)
    {
        $rules = array(
            'rating'  => 'required|digits_between:1,5',
            'review'  => 'nullable|min:3',
            'invoice' => 'required',
            'book_id' => 'required',
        );

        $request->validate($rules);
        $data            = $request->except('_token');
        $data['user_id'] = auth()->user()->id;
        $message         = 'Berhasil memberi rating / ulasan';

        Rating::create($data);

        return redirect()->back()->with('message', $message);
    }
}
