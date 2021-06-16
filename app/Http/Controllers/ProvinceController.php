<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\ { Province, City };
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function ajaxChangeProvince(Request $request) {
        $province  = Province::find($request->provinsi);
        $districts = City::firstWhere('province_id', $province->id)->districts;

        return response()->json(array('cities' => $province->cities, 'districts' => $districts, 'test' => $request->provinsi));
    }
}
