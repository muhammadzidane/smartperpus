<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function ajaxChangeCity(Request $request) {
        $districts = City::find($request->kota_atau_kabupaten)->districts;

        return response()->json(compact('districts'));
    }
}
