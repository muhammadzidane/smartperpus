<?php

namespace App\Http\Controllers;

use App\Models\BookUser;
use Illuminate\Http\Request;

class BookUserController extends Controller
{
    public function search(Request $request)
    {
        return response()->json(array('test' => $request->keywords));
    }

    public function update(BookUser $bookUser)
    {
        $update = array('payment_status' => 'failed');
        $bookUser->update($update);
        return response()->json()->status();
    }
}
