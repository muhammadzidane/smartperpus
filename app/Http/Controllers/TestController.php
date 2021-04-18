<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class TestController extends Controller
{
    public function test() {
        $j = 1;

        for ($i=0; $i < 10 ; $i+=5) {
            dump($j);
            dump($i);

            $j++;
        }
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
