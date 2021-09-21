<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\{Author, Book, Province, City, User, BookUser, UserChat, AdminChat, Category, Cart};
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Builder;

class TestController extends Controller
{
    public function test()
    {
        $ratings = array();

        for ($i = 0; $i < 100; $i++) {
            array_push($ratings, rand(1, 5));
        }

        $ratings_sum          = array_sum($ratings);
        $ratings_amount       = count($ratings);
        $rating_average_value = 2.2;

        for ($i = 1; $i <= 5; $i++) {
            $full = 'full';
            $half = 'half';
            $empty = 'empty';

            if ($rating_average_value >= $i) {
                // dump($full);
            } else if ($i >= $rating_average_value + 1) {
                // dump($empty);
            } else {
                // dump($half);
            }
        }
        // $book = Book::find(1);
        // dump($book->ratings);

        return view('test');
    }

    public function testPost(Request $request)
    {
        $test = Carbon::now();

        dump($test);
        // dd(true);
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
