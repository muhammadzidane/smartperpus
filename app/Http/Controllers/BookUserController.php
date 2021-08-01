<?php

namespace App\Http\Controllers;

use App\Models\BookUser;
use Carbon\Carbon;
use Facade\Ignition\DumpRecorder\DumpRecorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Validator, Auth};

class BookUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin.only');
    }

    public function show(BookUser $bookUser)
    {
        $bookUsers = BookUser::where('invoice', $bookUser->invoice)->get();
        $datas = array(
            'book_users' => $bookUsers,
        );
        $viewRender = view('modal.bill', $datas)->render();

        return response()->json(compact('bookUsers', 'viewRender'));
    }

    public function update(Request $request, BookUser $bookUser)
    {
        switch ($request->status) {
            case 'failed':
                $update = array('payment_status' => 'failed');
                $bookUser->update($update);

                return response()->json()->status();
                break;

            case 'uploadImage':
                $validation_rules = array(
                    'upload_payment' => array('required', 'mimes:jpg,png,jpeg', 'max:2000')
                );

                $validator = Validator::make($request->all(), $validation_rules);

                if ($validator->fails()) {
                    $errors = $validator->errors();

                    $success = false;
                    return response()->json(compact('errors', 'success'));
                } else {
                    $user          = Auth::user();
                    $path_store    = "$user->first_name-$user->last_name-$user->email-";
                    $path_store   .= time() . '.' . $request->upload_payment->getClientOriginalExtension();
                    $update           = array('upload_payment_image' => $path_store);

                    $bookUser->update($update);

                    if ($request->upload_payment && !Storage::exists('public/uploaded_payment/' . $path_store)) {
                        $request->upload_payment->storeAs('public/uploaded_payment', $path_store);
                    }

                    $success = true;

                    return response()->json(compact('success'));
                }
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

    public function confirmedOrders()
    {
        $book_users = BookUser::where('upload_payment_image', '!=', null)
            ->where('payment_status', 'order_in_process')
            ->where('confirmed_payment', true)->get();

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
        return view('book_user.status.arrived', compact('book_users'));
    }

    public function trackingPackages()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "waybill=030200010250720&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: ce496165f4a20bc07d96b6fe3ab41ded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response =  "cURL Error #:" . $err;
            return response()->json(compact('response'));
        } else {
            return response()->json(compact('response'));
        }
    }

    public function income()
    {
        $now = Carbon::now();
        $book_users = BookUser::where('payment_status', 'arrived')->get();

        // Penghasilan Hari ini
        $today_book_users = $book_users->map(function ($book_user) {
            $now           = Carbon::now();
            $nowStartOfDay = $now->startOfDay()->format('Y-m-d H:i:s');
            $nowEndOfDay   = $now->endOfDay()->format('Y-m-d H:i:s');

            $results = BookUser::whereBetween(
                'completed_date',
                [$nowStartOfDay, $nowEndOfDay]
            )
                ->where('id', $book_user->id)
                ->first();

            return $results;
        });

        $today_total_payments = $today_book_users->reduce(function ($carry, $item) {
            return $item == null ? 0 : $carry + $item->total_payment;
        });

        $today = array(
            'book_users' => $today_book_users,
            'count' => $today_total_payments == 0 ? 0 : $today_book_users->count(),
            'total_payments' => $today_total_payments,
        );

        // Penghasilan bulan ini
        $this_month_book_users = $book_users->map(function ($book_user) {
            $now             = Carbon::now();
            $nowStartOfMonth = $now->startOfMonth()->format('Y-m-d H:i:s');
            $nowEndOfMonth   = $now->endOfMonth()->format('Y-m-d H:i:s');

            return $book_user
                ->whereBetween(
                    'completed_date',
                    [
                        $nowStartOfMonth, $nowEndOfMonth
                    ]
                )
                ->first();
        });

        $this_month_total_payments = $this_month_book_users->reduce(function ($carry, $item) {
            return $item == null ? 0 : $carry + $item->total_payment;
        });

        $this_month = array(
            'book_users' => $this_month_book_users,
            'count' => $this_month_total_payments == 0 ? 0 : $this_month_book_users->count(),
            'total_payments' => $this_month_total_payments,
        );

        $all_total_payments = $book_users->reduce(function ($carry, $item) {
            return $carry + $item->total_payment;
        });

        // Semua pembayaran
        $all = array(
            'book_users' => $book_users,
            'total_payments' => $all_total_payments,
        );

        return view('book_user.status.income', compact('now', 'all', 'today', 'this_month'));
    }

    // Ajax GET
    // Penghasilan Hari ini
    public function ajaxToday()
    {
        $now        = Carbon::now();
        $book_users = BookUser::where('payment_status', 'arrived')->get();

        // Penghasilan Hari ini
        $today_book_users = $book_users->map(function ($book_user) {
            $now           = Carbon::now();
            $nowStartOfDay = $now->startOfDay()->format('Y-m-d H:i:s');
            $nowEndOfDay   = $now->endOfDay()->format('Y-m-d H:i:s');

            $results = BookUser::whereBetween(
                'completed_date',
                [$nowStartOfDay, $nowEndOfDay]
            )
                ->where('id', $book_user->id)
                ->first();

            return $results;
        });

        $today_total_payments = $today_book_users->reduce(function ($carry, $item) {
            return $item == null ? 0 : $carry + $item->total_payment;
        });

        $today = array(
            'book_users' => $today_book_users,
            'count' => $today_total_payments == 0 ? 0 : $today_book_users->count(),
            'total_payments' => $today_total_payments,
        );

        return response()->json(compact('today'));
    }
}
