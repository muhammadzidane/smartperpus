<?php

namespace App\Http\Controllers;

use App\Models\BookUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Validator};

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
        switch ($request->paymentMethod) {
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
                return response()->json(array('test' => true));
                break;
        }
    }

    public function uploadedPayments()
    {
        return view('book_user.status.upload-payment');
    }

    public function confirmedOrders()
    {
        $book_users = BookUser::where('upload_payment_image', '!=', null)
            ->where('payment_status', 'order_in_process')
            ->where('confirmed_payment', true)->get();

        // dd($book_users);
        return view('book_user.status.confirmed-orders', compact('book_users'));
    }
}
