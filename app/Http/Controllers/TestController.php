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
}
