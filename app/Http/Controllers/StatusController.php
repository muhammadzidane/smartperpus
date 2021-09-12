<?php

namespace App\Http\Controllers;

use App\Models\{User, Book, BookUser, Customer};
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        switch ($request->path()) {
                // Guest
            case 'status/all':
                $status_title = 'Semua';
                break;
            case 'status/failed':
                $payment_status = 'failed';
                $status_title = 'Dibatalkan';
                break;
            case 'status/unpaid':
                $payment_status = 'waiting_for_confirmation';
                $status_title = 'Belum Dibayar';
                break;
            case 'status/on-process':
                $payment_status = 'order_in_process';
                $status_title = 'Sedang Diproses';
                break;
            case 'status/on-delivery':
                $payment_status = 'being_shipped';
                $status_title = 'Sedang Dikirim';
                break;
            case 'status/completed':
                $payment_status = 'arrived';
                $status_title = 'Selesai';
                break;

                // Admin
            case 'status/uploaded-payment':
                $payment_status = 'waiting_for_confirmation';
                $status_title = 'Unggahan Bukti Pembayaran';
                break;
            default:

                break;
        }

        $user = User::find(Auth::id());

        if (auth()->user()->role == 'guest') {
            if ($request->path() != 'status/all') {
                $conditions = array(
                    array('user_id', $user->id),
                    array('payment_status', $payment_status)
                );
            } else {
                $conditions = array(
                    array('user_id', $user->id)
                );
            }
        } else { // Admin / Super Admin
            if ($request->path() != 'status/all') {
                $conditions = array(
                    array('payment_status', $payment_status),
                );

                // Unggahan bukti pembayaran
                if ($request->path() == 'status/uploaded-payment') {
                    $conditions = array(
                        array('payment_status', $payment_status),
                        array('upload_payment_image', '!=', null),
                    );
                }
            } else {
                $conditions = array();
            }
        }

        $book_users = BookUser::where($conditions)
            ->orderBy('created_at', 'DESC')
            ->get();

        $book_users = $book_users->unique('invoice');

        $book_users = $book_users->map(function ($book_user) {
            $book_user_invoice = BookUser::where('invoice', $book_user->invoice);

            $first_data    = $book_user_invoice->get()->first();
            $total_payment = $book_user_invoice->get()->sum('total_payment') + $first_data->unique_code + $first_data->shipping_cost;
            $amount        = $book_user_invoice->get()->sum('amount');


            if (auth()->user()->role == 'guest') {
                $books = auth()->user()->books->filter(fn ($book) => $book->pivot->invoice == $book_user->invoice);
            } else {
                $selected_book = array(
                    'books.*',
                    'book_user.book_version',
                    'book_user.amount',
                    'book_user.note'
                );

                $books = Book::join('book_user', 'books.id', '=', 'book_user.book_id')
                    ->select($selected_book)
                    ->where('book_user.invoice', $book_user->invoice)
                    ->get();
            }

            switch ($first_data->payment_status) {
                case 'failed':
                    $status = 'DIBATALKAN';
                    break;
                case 'waiting_for_confirmation':
                    $status = 'BELUM DIBAYAR';
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

                default:
                    # code...
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


        $data = compact('book_users', 'status_title');

        return view('status.index', $data);
    }

    public function detail($invoice)
    {
        $book_user     = BookUser::firstWhere('invoice', $invoice);
        $customer      = Customer::find($book_user->customer_id);
        $total_payment = BookUser::where('invoice', $invoice)->sum('total_payment');
        $total_payment = $total_payment + $book_user->unique_code + $book_user->shipping_cost;

        $data = array(
            'book_user'     => $book_user,
            'customer'      => $customer,
            'district'      => $customer->district->name,
            'city'          => $customer->city->name,
            'city_type'     => $customer->city->type,
            'province'      => $customer->province->name,
            'total_payment' => $total_payment,
        );

        $response = array(
            'status' => 'success',
            'code'   => 200,
            'data'   => $data,
        );

        return response()->json($response);
    }

    public function confirmUploadPayment($invoice)
    {
        $data = array(
            'payment_status' => 'order_in_process',
            'confirmed_payment' => true,
        );

        $status_exists = BookUser::where('invoice', $invoice)->exists();

        if ($status_exists) {
            $book_users = BookUser::where('invoice', $invoice)->update($data);

            $data = array(
                'book_users' => $book_users,
                'invoice'    => $invoice,
            );

            $response = array(
                'status'  => 'success',
                'code'    => 200,
                'data'    => null,
                'message' => 'Berhasil menkonfirmasi bukti transfer, dan akan segera diproses'
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
}
