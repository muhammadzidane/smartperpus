<?php

namespace App\Http\Controllers;

use App\Models\{BookUser, Book};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Validator, Auth};
use Illuminate\Pagination\LengthAwarePaginator;

class BookUserController extends Controller
{
    public function __construct()
    {
        $admin_only_middleware = array(
            'uploadedPayments',
            'incomeDetail',
            'confirmedOrders',
            'onDelivery',
            'arrived',
        );

        $this->middleware('auth');
        $this->middleware('auth.admin.only')->only($admin_only_middleware);
    }

    public function update(Request $request, BookUser $bookUser)
    {
        switch ($request->status) {
            case 'failed':
                $update = array('payment_status' => 'failed');
                $bookUser->update($update);

                return response()->json()->status();
                break;

            case 'orderInProcess':
                $update = array(
                    'payment_status' => 'order_in_process',
                    'confirmed_payment' => true,
                );

                $bookUser->update($update);
                return response()->json()->status();
                break;

            case 'orderOnDelivery':
                $update = array('payment_status' => 'being_shipped');

                $bookUser->update($update);

                return response()->json()->status();
                break;

            case 'arrived':
                $now = Carbon::now();

                $update = array(
                    'payment_status' => 'arrived',
                    'completed_date' => $now->format('Y-m-d H:i:s'),
                );

                $bookUser->update($update);

                return response()->json()->status();
                break;

            case 'cancelProcessConfirmation':
                $update = array(
                    'payment_status' => 'waiting_for_confirmation',
                    'confirmed_payment' => false,
                );

                $bookUser->update($update);

                return response()->json()->status();
                break;

            case 'cancelUploadImage':
                $update = array(
                    'upload_payment_image' => null,
                );

                $bookUser->update($update);

                return response()->json()->status();
                break;
        }
    }

    public function uploadedPayments()
    {
        $book_users = BookUser::where('upload_payment_image', '!=', null)
            ->where('payment_status', 'waiting_for_confirmation')->get();

        return view('book_user.status.upload-payment', compact('book_users'));
    }

    public function confirmedOrders(Request $request)
    {
        $book_users = BookUser::where('upload_payment_image', '!=', null)
            ->where('payment_status', 'order_in_process')
            ->where('confirmed_payment', true)->get();

        $book_users = $book_users->unique('invoice');
        $book_users = $book_users->map(function ($book_user) {
            $amount        = BookUser::where('invoice', $book_user->invoice)->get()->sum('amount');
            $total_payment = BookUser::where('invoice', $book_user->invoice)->get()
                ->sum('total_payment') + $book_user->unique_code + $book_user->shipping_cost;

            return array(
                'invoice' => $book_user->invoice,
                'book_user' => $book_user,
                'amount' => $amount,
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

        dump($book_users);

        return view('book_user.status.confirmed-orders', compact('book_users'));
    }

    public function onDelivery()
    {
        $auth_id = Auth::id();

        if (Auth::user()->role != "guest") {
            // Ambil semua data
            $book_users = BookUser::where('upload_payment_image', '!=', null)
                ->where('payment_status', 'being_shipped')
                ->get();
        } else {
            $book_users = BookUser::where('upload_payment_image', '!=', null)
                ->where('user_id', $auth_id)
                ->where('payment_status', 'being_shipped')
                ->get();
        }

        return view('book_user.status.on-delivery', compact('book_users'));
    }

    public function arrived()
    {
        $book_users = BookUser::where('payment_status', 'arrived')->get();
        $book_users = $book_users->unique('invoice');

        dump($book_users);
        // return view('book_user.status.arrived', compact('book_users'));
    }
}
