<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index() {
        return view('user/my-account');
    }

    public function transactionLists() {
        return view('account.transaction-lists');
    }

    public function myReviews() {
        return view('account.my-reviews');
    }

    public function waitingForPayments() {
        return view('account.waiting-for-payments');
    }

    public function chat() {
        return view('account.chat');
    }

    public function addNewAccount() {
        return view('account.add-account');
    }
}
