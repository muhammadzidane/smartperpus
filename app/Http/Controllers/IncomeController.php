<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookUser;
use Carbon\Carbon;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin.only');
    }

    public function incomeMonthly()
    {
        return view('book_user.status.income-details');
    }
}
