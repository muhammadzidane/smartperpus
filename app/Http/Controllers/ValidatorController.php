<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ValidatorController extends Controller
{
    public function unique(Request $request)
    {
        $table = DB::table($request->table)->where($request->row, $request->inputValue)->first();

        return response()->json(compact('table'));
    }
}
