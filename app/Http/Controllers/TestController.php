<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class TestController extends Controller
{
    public function test() {
        return view('');
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
