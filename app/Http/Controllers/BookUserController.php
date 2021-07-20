<?php

namespace App\Http\Controllers;

use App\Models\BookUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Validator, Auth};

class BookUserController extends Controller
{
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
                    $upload_file_name = $request->upload_payment ? $request->upload_payment->getClientOriginalName() : null;

                    $update           = array('upload_payment_image' => $upload_file_name);
                    $bookUser->update($update);

                    if ($request->upload_payment && !Storage::exists('public/uploaded_payment/' . $upload_file_name)) {
                        $request->upload_payment->storeAs('public/uploaded_payment', $upload_file_name);
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
}
