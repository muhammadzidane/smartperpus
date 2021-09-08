<?php

namespace App\Http\Controllers;

use App\Models\{User, BookUser};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function failed()
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

        $book_users->values()->all();

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

        return view('status.failed', compact('user', 'book_users', 'record', 'counts',));
    }

    public function waitingForPayments()
    {
        $user       = User::find(Auth::id());
        $record     = 'waiting_for_payment';
        $book_users = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'waiting_for_confirmation')
            ->where('confirmed_payment', false)
            ->orderBy('created_at', 'DESC')
            ->get();

        $failed_count = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'failed')
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
            'waiting_for_payment' => $book_users->count(),
            'on_process' => $on_process_count,
            'on_delivery' => $on_delivery_count,
            'arrived' => $arrived_count,
        );

        $waiting_for_payment = true;

        return view('status.waiting-for-payments', compact('user', 'book_users', 'record', 'counts', 'waiting_for_payment'));
    }

    public function onProcess()
    {
        $user       = User::find(Auth::id());
        $record     = 'on_process';
        $book_users = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'order_in_process')
            ->orderBy('created_at', 'DESC')
            ->get();

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

    public function onDelivery()
    {
        $user       = User::find(Auth::id());
        $book_users = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'being_shipped')
            ->orderBy('created_at', 'DESC')
            ->get();

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

    public function success()
    {
        $user       = User::find(Auth::id());
        $book_users = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'arrived')
            ->orderBy('created_at', 'DESC')
            ->get();

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
}
