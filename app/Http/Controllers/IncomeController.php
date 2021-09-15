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

    public function income(Request $request)
    {
        $now              = Carbon::now();
        $nowStartOfDay    = $now->startOfDay()->format('Y-m-d H:i:s');
        $nowEndOfDay      = $now->endOfDay()->format('Y-m-d H:i:s');
        $today_book_users = BookUser::where('payment_status', 'arrived')
            ->whereBetween('completed_date', [$nowStartOfDay, $nowEndOfDay])
            ->get();

        $today_total_payments = $today_book_users->reduce(function ($carry, $item) {
            return $item == null ? 0 : $carry + $item->total_payment;
        });

        $today = array(
            'book_users'     => $today_book_users,
            'count'          => $today_total_payments == 0 ? 0 : $today_book_users->count(),
            'total_payments' => $today_total_payments,
        );

        $test = BookUser::where('payment_status', 'arrived')
            ->whereBetween('completed_date', [$nowStartOfDay, $nowEndOfDay])
            ->get();

        dump($nowStartOfDay);

        // Penghasilan bulan ini
        $nowStartOfMonth       = $now->startOfMonth()->format('Y-m-d H:i:s');
        $nowEndOfMonth         = $now->endOfMonth()->format('Y-m-d H:i:s');
        $this_month_book_users = BookUser::where('payment_status', 'arrived')
            ->whereBetween('completed_date', [$nowStartOfMonth, $nowEndOfMonth])
            ->get();

        $this_month_total_payments = $this_month_book_users->reduce(function ($carry, $item) {
            return $item == null ? 0 : $carry + $item->total_payment;
        });

        $this_month = array(
            'book_users' => $this_month_book_users,
            'count' => $this_month_total_payments == 0 ? 0 : $this_month_book_users->count(),
            'total_payments' => $this_month_total_payments,
        );

        $all_income_book_users = BookUser::where('completed_date', '!=', null)
            ->where('payment_status', 'arrived')
            ->get();

        $all_total_payments = $all_income_book_users->reduce(function ($carry, $item) {
            return $carry + $item->total_payment;
        });

        // Semua pembayaran
        $all = array(
            'book_users' => $all_income_book_users,
            'total_payments' => $all_total_payments,
        );

        $data = compact('now', 'all', 'today', 'this_month');

        // Search
        if ($request->month) {
            $rules = array(
                'month' => 'required|date_format:Y-m'
            );

            $request->validate($rules);

            $search              = Carbon::create($request->month);
            $search_start_of_day = $search->startOfDay()->format('Y-m-d H:i:s');
            $search_end_of_day   = $search->endOfMonth()->format('Y-m-d H:i:s');

            $search_book_users = BookUser::where('payment_status', 'arrived')
                ->whereBetween('completed_date', [$search_start_of_day, $search_end_of_day])
                ->get();

            $search_total_payments = $search_book_users->reduce(function ($carry, $item) {
                return $item == null ? 0 : $carry + $item->total_payment;
            });

            $search = array(
                'book_users' => $search_book_users,
                'count' => $search_total_payments == 0 ? 0 : $search_book_users->count(),
                'total_payments' => $search_total_payments,
            );

            $data = compact('now', 'all', 'today', 'this_month', 'search');
        }

        return view('book_user.status.income', $data);
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
