<?php

namespace App\Http\Controllers;

use App\Models\{User, Book, BookUser, Customer};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StatusController extends Controller
{
    public function failed(Request $request)
    {
        $user       = User::find(Auth::id());
        $record     = 'waiting_for_payment';
        $conditions = array(
            array('user_id', $user->id),
            array('payment_status', 'failed'),
        );

        $book_users = BookUser::where($conditions)
            ->orderBy('created_at', 'DESC')
            ->get();

        $book_users = $book_users->unique('invoice');
        $book_users = $book_users->map(function ($book_user) {
            $total_payment = BookUser::where('invoice', $book_user->invoice)->get()->sum('total_payment');

            return array(
                'invoice' => $book_user->invoice,
                'book_user' => $book_user,
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

        $waiting_for_payment_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'waiting_for_confirmation')
            ->where('confirmed_payment', false)
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        $on_process_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'order_in_process')
            ->get()->count();

        $on_delivery_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'being_shipped')
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        $arrived_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'arrived')
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        $counts = array(
            'failed' => $book_users->count(),
            'waiting_for_payment' => $waiting_for_payment_count,
            'on_process' => $on_process_count,
            'on_delivery' => $on_delivery_count,
            'arrived' => $arrived_count,
        );

        $waiting_for_payment = true;

        $data = compact('book_users', 'record', 'counts');

        return view('status.failed', $data);
    }

    public function waitingForPayments(Request $request)
    {
        $user       = User::find(Auth::id());
        $book_users = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'waiting_for_confirmation')
            ->where('confirmed_payment', false)
            ->orderBy('created_at', 'DESC')
            ->get();

        $book_users = $book_users->unique('invoice');

        $book_users = $book_users->map(function ($book_user) {
            $book_user_invoice = BookUser::where('invoice', $book_user->invoice);

            $first_data    = $book_user_invoice->get()->first();
            $total_payment = $book_user_invoice->get()->sum('total_payment') + $first_data->unique_code + $first_data->shipping_cost;
            $amount        = $book_user_invoice->get()->sum('amount');
            $books         = auth()->user()->books->filter(fn ($book) => $book->pivot->invoice == $book_user->invoice);

            return array(
                'first'         => $first_data,
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

        $data = compact('book_users');

        return view('status.waiting-for-payments', $data);
    }

    public function onProcess(Request $request)
    {
        $user       = User::find(Auth::id());
        $record     = 'on_process';
        $book_users = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'order_in_process')
            ->orderBy('created_at', 'DESC')
            ->get();


        $book_users = $book_users->unique('invoice');
        $book_users = $book_users->map(function ($book_user) {
            $total_payment = BookUser::where('invoice', $book_user->invoice)->get()->sum('total_payment') + $book_user->unique_code;

            return array(
                'invoice' => $book_user->invoice,
                'book_user' => $book_user,
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

        $failed_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'failed')
            ->get()->count();

        $waiting_for_payment_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'waiting_for_confirmation')
            ->where('confirmed_payment', false)
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        $on_process_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'order_in_process')
            ->get()->count();

        $on_delivery_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'being_shipped')
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        $arrived_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'arrived')
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        $counts = array(
            'failed' => $failed_count,
            'waiting_for_payment' => $waiting_for_payment_count,
            'on_process' => $book_users->count(),
            'on_delivery' => $on_delivery_count,
            'arrived' => $arrived_count,
        );

        return view('status.on-process', compact('user', 'book_users', 'record', 'counts'));
    }

    public function onDelivery(Request $request)
    {
        $user       = User::find(Auth::id());
        $book_users = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'being_shipped')
            ->orderBy('created_at', 'DESC')
            ->get();

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

        $failed_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'failed')
            ->get()->count();

        $waiting_for_payment_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'waiting_for_confirmation')
            ->where('confirmed_payment', false)
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        $on_process_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'order_in_process')
            ->get()->count();

        $arrived_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'arrived')
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        $counts = array(
            'failed' => $failed_count,
            'waiting_for_payment' => $waiting_for_payment_count,
            'on_process' => $on_process_count,
            'on_delivery' => $book_users->count(),
            'arrived' => $arrived_count,
        );

        $on_delivery = true;

        return view('status.on-delivery', compact('user', 'book_users', 'counts', 'on_delivery'));
    }

    public function success(Request $request)
    {
        $user       = User::find(Auth::id());
        $book_users = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'arrived')
            ->orderBy('created_at', 'DESC')
            ->get();

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

        $book_users = $book_users->unique('invoice');

        $failed_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'failed')
            ->get()->count();

        $waiting_for_payment_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'waiting_for_confirmation')
            ->where('confirmed_payment', false)
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        $on_process_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'order_in_process')
            ->get()->count();

        $on_delivery_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'being_shipped')
            ->orderBy('created_at', 'DESC')
            ->get()->count();


        $counts = array(
            'failed' => $failed_count,
            'waiting_for_payment' => $waiting_for_payment_count,
            'on_process' => $on_process_count,
            'on_delivery' => $on_delivery_count,
            'arrived' => $book_users->count(),
        );

        $on_delivery = true;

        return view('status.success', compact('user', 'book_users', 'counts'));
    }

    public function detail($invoice)
    {
        $selected_columns = array(
            'payment_method',
            'courier_name',
            'courier_service',
            'unique_code',
            'customer_id',
        );

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
}
