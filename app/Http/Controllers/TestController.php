<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\{Author, Book, Province, City, User, BookUser, UserChat, AdminChat, Category, Cart};
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class TestController extends Controller
{
    public function test()
    {

    }

    public function testPost(Request $request)
    {
        $client = new Client([
            'base_uri' => 'https://pro.rajaongkir.com',
        ]);

        $options = [
            'json' => [
                'key' => 'ce496165f4a20bc07d96b6fe3ab41ded',
                'origin' => 317,
                'originType' => 'subdistrict',
                'destination' => 1416,
                'destinationType' => 'subdistrict',
                'weight' => 300,
                'courier' => 'jne',
            ],
            'verify' => false,
        ];

        $response = $client->request('POST', '/api/cost', $options);

        $body = $response->getBody();
        $body = json_decode($body);

        return response()->json($body);
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
