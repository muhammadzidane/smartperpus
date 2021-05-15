<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index() {
        return view('account/my-account');
    }

    public function transactionLists() {
        return view('account.transaction-lists');
    }
}
