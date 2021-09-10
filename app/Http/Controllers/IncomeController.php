<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin.only');
    }

    public function incomeDetailToday()
    {
        $now              = Carbon::now();
        $nowStartOfDay    = $now->startOfDay()->format('Y-m-d H:i:s');
        $nowEndOfDay      = $now->endOfDay()->format('Y-m-d H:i:s');
        $today_book_users = BookUser::where('payment_status', 'arrived')
            ->whereBetween('completed_date', [$nowStartOfDay, $nowEndOfDay])
            ->orderBy('created_at')
            ->paginate(1);

        $today_total_payments = $today_book_users->reduce(function ($carry, $item) {
            return $item == null ? 0 : $carry + $item->total_payment;
        });

        $data = array(
            'book_users'     => $today_book_users,
            'count'          => $today_total_payments == 0 ? 0 : $today_book_users->count(),
            'total_payments' => $today_total_payments,
        );

        return view('book_user.status.income-details', $data);
    }

    public function incomeDetailThisMonth()
    {
        $now                   = Carbon::now();
        $nowStartOfMonth       = $now->startOfMonth()->format('Y-m-d H:i:s');
        $nowEndOfMonth         = $now->endOfMonth()->format('Y-m-d H:i:s');
        $this_month_book_users = BookUser::where('payment_status', 'arrived')
            ->whereBetween('completed_date', [$nowStartOfMonth, $nowEndOfMonth])
            ->orderBy('created_at')
            ->paginate(10);

        $this_month_total_payments = $this_month_book_users->reduce(function ($carry, $item) {
            return $item == null ? 0 : $carry + $item->total_payment;
        });

        $data = array(
            'book_users' => $this_month_book_users,
            'count' => $this_month_total_payments == 0 ? 0 : $this_month_book_users->count(),
            'total_payments' => $this_month_total_payments,
        );

        return view('book_user.status.income-details', $data);
    }
}
