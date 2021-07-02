<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Author, Book, Province, City, User, BookUser, UserChat, AdminChat};
use Illuminate\Support\Facades\Date;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class TestController extends Controller
{
    public function test() {
        $request = '';


        if($request != '') {
            $query  = '';
            $query .= " SELECT user_chats.* FROM user_chats INNER JOIN users ON user_chats.user_id=users.id,";
            $query .= ' (SELECT user_id,max(created_at) AS transaction_date FROM user_chats GROUP BY user_id) max_user';
            $query .= ' WHERE user_chats.user_id = max_user.user_id';
            $query .= " AND user_chats.created_at = max_user.transaction_date";
            $query .= " AND concat_ws(' ', users.first_name, users.last_name) LIKE '%$request%'";
        }
        else {
            $query  = '';
            $query .= " SELECT user_chats.* FROM user_chats INNER JOIN users ON user_chats.user_id=users.id,";
            $query .= ' (SELECT user_id,max(created_at) AS transaction_date FROM user_chats GROUP BY user_id) max_user';
            $query .= ' WHERE user_chats.user_id = max_user.user_id';
            $query .= " AND user_chats.created_at = max_user.transaction_date";
            $query .= " AND ( users.first_name LIKE '%%' )";
        }


        $chats = DB::select($query);

        $userChatHtml  = "";

        foreach ($chats as $chat) {
            $userChatHtml .= "<div class='user-chat pl-3 py-3'";
            $userChatHtml .= "data-id='". User::find($chat->user_id)->id  ."'>";
            $userChatHtml .= "<div class='tbold text-grey'>" . User::find($chat->user_id)->first_name . ' ' ;
            $userChatHtml .=  User::find($chat->user_id)->last_name . "</div>";
            $userChatHtml .= "<div>" . strlen($chat->text) <= 28 ? $chat->text : substr($chat->text, 1, 28) . '..' . "</div>";
            $userChatHtml .= "</div>";
        }

        dump($chats);

        // return response()->json(compact('userChatHtml'));
    }

    public function testPost() {
        return 'wkwkwkwkw123';
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
