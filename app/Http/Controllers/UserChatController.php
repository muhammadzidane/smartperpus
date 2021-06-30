<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserChatController extends Controller
{
    public function store(Request $request) {
        $user            = User::find(Auth::id());
        $user_role_check = $user->role == 'guest' ? 'guest' : 'admin';
        $create          = array('text' => $request->message);

        if ($user_role_check == 'guest') {
            $user_chat       = $user->user_chats()->create($create);
        }

        $iso_format_chat = $user_chat->created_at->isoFormat('dddd, D MMMM YYYY H:MM');

        $user_role               = $user->role;
        $anda_or_admin           = $user_role == 'guest' ? 'Anda' : 'Admin';
        $text_right_or_left      = $user_role == 'guest' ? 'text-right' : 'text-left';
        $chat_msg_user_or_admin  = $user_role == 'guest' ? 'chat-msg-user' : 'chat-msg-admin';
        $chat_text_user_or_admin = $user_role == 'guest' ? 'chat-text-user' : 'chat-text0-admin';

        $chat  = "<div class='mt-3'>";
        $chat .= "<div class='$text_right_or_left'><small><span class='tbold'>$anda_or_admin</span>, $iso_format_chat WIB</small></div>";
        $chat .= "<div class='$chat_msg_user_or_admin'>";
        $chat .= "<div class='$chat_text_user_or_admin'>$request->message</div>";
        $chat .= "</div>";
        $chat .= "</div>";

        return response()->json(compact('chat'));
    }

    public function show(UserChat $userChat) {
        $user_chats       = $userChat->user->user_chats;

        $userChatsHtml = '';

        foreach ($user_chats as $user_chat) {
            $userChatsHtml  .= '<div class="mt-3">';
            $userChatsHtml  .= '<div class="text-right">';
            $userChatsHtml  .= '<small>';
            $userChatsHtml  .= '<span class="tbold">' . $user_chat->user->first_name . ' ' . $user_chat->user->last_name  .  ', </span>';
            $userChatsHtml  .= '<span>' . $user_chat->created_at->isoFormat('dddd, D MMMM YYYY H:MM') . ' WIB</span>';
            $userChatsHtml  .= '</small>';
            $userChatsHtml  .= '</div>';
            $userChatsHtml  .= '<div class="chat-msg-user">';
            $userChatsHtml  .= '<div class="chat-text-user">'. $user_chat->text . '</div>';
            $userChatsHtml  .= '</div>';
            $userChatsHtml  .= '</div>';
        }


        return response()->json(compact('userChatsHtml'));
    }
}

