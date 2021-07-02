<?php

namespace App\Http\Controllers;

use App\Models\ { User, UserChat };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{ Auth, DB };

class AdminChatController extends Controller
{
    public function store(Request $request) {
        $user  = User::find($request->userId);
        $admin = Auth::user();

        $create     = array('text' => $request->message);
        $admin_chat = $user->admin_chats()->create($create);
        $user_chat  = $user->user_chats;

        $iso_format_chat = $admin_chat->created_at->isoFormat('dddd, D MMMM YYYY H:MM');

        $chat  = "<div class='mt-3'>";
        $chat .= "<div class='text-right'><small><span class='tbold'>Anda </span>$iso_format_chat, WIB</small></div>";
        $chat .= "<div class='chat-msg-user'>";
        $chat .= "<div class='chat-text-user'>$request->message</div>";
        $chat .= "</div>";
        $chat .= "</div>";

        return response()->json(compact('chat'));
    }

    public function show(Request $request) {
        $query  = '';
        $query .= " SELECT user_chats.* FROM user_chats INNER JOIN users ON user_chats.user_id=users.id,";
        $query .= ' (SELECT user_id,max(created_at) AS transaction_date FROM user_chats GROUP BY user_id) max_user';
        $query .= ' WHERE user_chats.user_id = max_user.user_id';
        $query .= " AND user_chats.created_at = max_user.transaction_date";
        $query .= " AND concat_ws(' ', users.first_name, users.last_name) LIKE '%$request->searchVal%'";

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

        return response()->json(compact('userChatHtml'));
    }
}
