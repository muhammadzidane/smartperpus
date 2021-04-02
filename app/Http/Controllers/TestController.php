<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book};

class TestController extends Controller
{
    public function test() {
        $test = \App\Models\Book::count();

        // dump($test);
        return view('test');
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
