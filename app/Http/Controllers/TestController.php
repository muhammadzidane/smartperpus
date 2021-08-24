<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\{Author, Book, Province, City, User, BookUser, UserChat, AdminChat, Category, Cart};
use Faker\Factory as Faker;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $keywords = $request->keywords;

        $selects = array(
            'provinces.name AS province_name',
            'cities.type',
            'cities.name AS city_name',
            'districts.name AS district_name'
        );

        $address = DB::table('provinces')
            ->join('cities', 'provinces.id', '=', 'cities.province_id')
            ->join('districts', 'cities.id', '=', 'districts.city_id')
            ->where('cities.name', 'LIKE', "%bandung%")
            ->orWhere('districts.name', 'LIKE', "%bandung%")
            ->select($selects)
            ->get();

        $request_address = $address->map(function ($address) {
            $province = $address->province_name;
            $type     = $address->type == 'Kabupaten' ? 'Kab.' : 'Kota';
            $city     = $address->city_name;
            $district = $address->district_name;

            $results = $province . ', ' . $type . ' ' . $city . ', Kec.' . $district;

            return $results;
        });
    }

    public function testPost(Request $request)
    {
        // $request->photo->store('public/test');
        // return response()->json(array('photo' => $request->photo));
        dd(true);
    }

    public function pagination()
    {
        return view('pagination');
    }

    public function ajaxRequestStore()
    {
        return 'wkwkwk';
    }

    public function index()
    {
        $msg = 'this is a simple message';
        return response()->json(array('msg' => $msg), 200);
    }

    public function testCurl()
    {
        // $curlopt_postfield  = 'origin=' . $request->origin_id . '&originType=' . $request->origin_type;
        // $curlopt_postfield .= '&destination=' . $request->destination_id . '&destinationType=' . $request->destination_type;
        // $curlopt_postfield .= '&weight=' . $request->weight . '&courier=' . $request->courier;
        $curlopt_postfield =

            $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $curlopt_postfield,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: ce496165f4a20bc07d96b6fe3ab41ded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}
