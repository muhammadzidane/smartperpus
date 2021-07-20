<?php

namespace App\Http\Controllers;

use App\Models\{User, BookUser};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    // Menunggu Pembayaran
    public function waitingForPayments()
    {
        $user      = User::find(Auth::id());

        $book_users = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'waiting_for_confirmation')
            ->where('confirmed_payment', false)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('status.waiting-for-payments', compact('user', 'book_users'));
    }
}
