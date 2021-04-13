<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book};
use Illuminate\Support\Facades\Route;


class TestController extends Controller
{
    public function test() {
        $books = \App\Models\Book::where('name', 'LIKE', '%' . 'jujutsu' . '%')->get();

        $arr_categories = array();

        foreach ($books as $book) {
            foreach ($book->categories as $category) {
                array_push($arr_categories, $category->name);
            }

        }

        $tests = array_count_values($arr_categories);

        foreach ($tests as $key => $test) {
            dump($key);
        }

        dump(Route::has('test'));
    }

    public function pagination() {
        return view('pagination');
    }

    public function ajaxRequestStore() {
        return 'wkwkwk';
    }

    public function index() {
        $msg = 'this is a simple message';
        return response()->json(array('msg' => $msg), 200);

    }
}
