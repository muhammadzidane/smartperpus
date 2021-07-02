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
        $user        = User::find(2);
        $admin_chats = AdminChat::where('user_id', $user->id)->get();

        foreach ($admin_chats as $chattings) {
            $user->user_chats->push($chattings);
        }

        $userChatsHtml           = '';

        foreach ($user->user_chats->sortBy('created_at')  as $key => $chat) {
            $text_align      = $chat->getTable() == 'user_chats' ? 'text-left' : 'text-right';
            $first_name      = $chat->getTable() == 'user_chats' ? $chat->user->first_name : 'Admin';
            $last_name       = $chat->getTable() == 'user_chats' ? $chat->user->last_name : '';
            $chat_msg        = $chat->getTable() == 'user_chats' ? 'chat-msg-admin' : 'chat-msg-user';
            $chat_msg_align  = $chat->getTable() == 'user_chats' ? 'chat-text-admin' : 'chat-text-user';
            $iso_format_chat = $chat->created_at->isoFormat('dddd, D MMMM YYYY H:MM');

            $userChatsHtml .= "<div class='mt-3'>";
            $userChatsHtml .= "<div class='$text_align'><small><span class='tbold'>$first_name $last_name </span>$iso_format_chat, WIB</small></div>";
            $userChatsHtml .= "<div class='$chat_msg'>";
            $userChatsHtml .= "<div class='$chat_msg_align'>$chat->text</div>";
            $userChatsHtml .= "</div>";
            $userChatsHtml .= "</div>";

            dump($chat->created_at->isoFormat('dddd, D MMMM YYYY H:MM'));
        }
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
