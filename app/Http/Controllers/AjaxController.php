<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book, User};
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

    // Validasi untuk mengecek user
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

    // Validasi register user
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

    // Filter minimal dan maksimal harga
    public function filterSearch(Request $request) {
        $min_price = $request->min_price != '' ? $request->min_price : 0;
        $max_price = $request->max_price != '' ? $request->max_price : 9999999999;


        $where_queries = array(
            array('name', 'like', '%' . $request->keywords . '%'),
        );

        if ($request->star_value) {
            array_push($where_queries, array('rating', '>=', $request->star_value));
        };

        $view = view('layouts.books',
            array(
                'books' =>
                \App\Models\Book::whereBetween('price', array($min_price, $max_price))
                ->where($where_queries)->get()
            )
        )->render();


        return response()->json(
            array('books' => $view, 'min' => $min_price, 'max' => $max_price)
        );
    }

    public function filterStar(Request $request) {
        $view = view('layouts.books',
            array(
                'books' =>
                \App\Models\Book::where('rating', '>=', (int) $request->star_value)->get()
            )
        )->render();

        return response()->json(
            array('books' => $view)
        );
    }
}
