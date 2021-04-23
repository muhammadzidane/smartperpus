<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book, User};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AjaxController extends Controller
{
    public function firstLoad(Request $request) {
        $j = 1;

        for ($i=0; $i <= Book::get()->count(); $i+=5) {
            if ($j == $request->page) {
                $books = view('layouts.books',
                    array(
                        'books' => Book::take(5)->skip($i)->get()
                    )
                )->render();
            }

            $j++;
        }

        return response()->json(compact('books'));
    }

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
        $min_price = $request->min_price != 0 ? $request->min_price : 0;
        $max_price = $request->max_price != '' ? $request->max_price : 9999999999;


        $where_queries = array(
            array('name', 'like', '%' . $request->keywords . '%'),
        );

        if ($request->star_value !== null) {
            array_push($where_queries, array('rating', '>=', (int) $request->star_value));
        };

        $books = array(
            'books' =>
            \App\Models\Book::whereBetween('price', array($min_price, $max_price))
            ->where($where_queries)->get()
        );

        if ($request->sort_book_value) {
            switch ($request->sort_book_value) {
                case 'highest-rating':
                    $books = array(
                        'books' =>
                        \App\Models\Book::whereBetween('price', array($min_price, $max_price))
                        ->where($where_queries)->orderBy('rating', 'DESC')->get()
                    );
                break;
                case 'highest-price':
                    $books = array(
                        'books' =>
                        \App\Models\Book::whereBetween('price', array($min_price, $max_price))
                        ->where($where_queries)->orderBy('price', 'DESC')->get()
                    );
                break;
                case 'lowest-price':
                    $books = array(
                        'books' =>
                        \App\Models\Book::whereBetween('price', array($min_price, $max_price))
                        ->where($where_queries)->orderBy('price', 'ASC')->get()
                    );
                break;
            }
        }

        $view = view('layouts.books', $books)->render();

        return response()->json(
            array('books' => $view , 'page' => $request->page)
        );
    }

    // Filter rating 4 ke atas
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

    public function search(Request $request) {
        $books = Book::where('name', 'LIKE', '%' . $request->keywords . '%')->get();

        $view = view('layouts.books',
            array(
                'books' => $books,
            )
        )->render();


        $book_category = array();

        foreach ($books as $book) {
            foreach ($book->categories as $category) {
                array_push($book_category, $category->name);
            }
        }

        $book_category = array_count_values($book_category);

        return response()->json(
            array('books' => $view, 'bookCategory' => $book_category)
        );
    }

    public function sortBooks(Request $request) {
        switch ($request->sort_book_value) {
            case 'highest-rating':
                $books = Book::orderBy('rating', 'DESC')->get();
            break;
            case 'highest-price':
                $books = Book::orderBy('price', 'DESC')->get();
            break;
            case 'lowest-price':
                $books = Book::orderBy('price', 'ASC')->get();
            break;
        }

        $view = view('layouts.books',
            array(
                'books' => $books,
            )
        )->render();

        return response()->json(
            array('books' => $view)
        );
    }


    // Pagination
    public function paginationData() {
        $book_count = Book::get()->count();

        $pagination_html     = function($value) {
            return '<div id=\'page-' . $value . '\'>'  . $value .  '</div>';
        };

        $i                   = 1;
        $arr_pagination_html = array();

        for ($j=1; $j <= $book_count; $j+=5) {
            array_push($arr_pagination_html, $pagination_html($i++));
        }

        return response()->json(
            array('paginationHtml' => $arr_pagination_html)
        );
    }

    public function pagination(Request $request) {
        $book_count = Book::get()->count();

        $i= 1;

        for ($j=0; $j < $book_count; $j+=5) {
            if ($request->page == $i) {
                $view = view('layouts.books',
                    array(
                        'books' => Book::take(5)->skip($j)->get()
                    )
                )->render();
            }

            $i++;
        }


        return response()->json(
            array('books' => $view)
        );
    }
}
