<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book, User};
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AjaxController extends Controller
{
    public function ajaxRequestStore(Request $request) {
        $authors = Author::where('name', 'like', '%' .$request->search_value . '%')
        ->orderBy('name')->get(array('id', 'name'))->take(5);

        $books   = Book::where('name', 'like', '%' .$request->search_value . '%')
        ->orderBy('name')->get(array('id', 'name'))->take(5);

        return response()->json(
            array(
                'books'   => $books,
                'authors' => $authors,
            )
        );
    }

    public function checkLogin(Request $request) {
        $user     = User::where(
            array(
                array('email', $request->email),
            )
        )->first( array('email', 'password'));

        $check_password = $user !== null ? Hash::check($request->password, $user->password) : '';

        if (!$check_password || $user == null) {
            return response()->json(
                array(
                    'message' => 'Email / Password anda salah',
                    'success' => false,
                )
            );
        }
        else {
            return response()->json(
                array(
                    'success' => true,
                )
            );
        }
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(),
            array(
                'nama_awal'           => 'required|string',
                'nama_akhir'          => 'required|string',
                'email'               => 'required|email|unique:users,email',
                'password'            => 'required|min:6',
                'konfirmasi_password' => 'required|min:6|same:password',
            )
        );

        if ($validator->fails()) {

            return response()->json(
                array('errors' => $validator->errors())
            );
        }
        else {
            return response()->json(
                array('errors' => '')
            );
        }
    }
}
