<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class TestController extends Controller
{
    public function test() {
        // return view('test');
        // return 'wkwk';
        dump(Route::currentRouteName());
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

    public function testCurl() {
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
        }
        else {
            echo $response;
        }
    }
}
