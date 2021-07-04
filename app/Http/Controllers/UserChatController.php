<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\{ UserChat, AdminChat };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserChatController extends Controller
{
    public function store(Request $request) {
        $user            = User::find(Auth::id());
        $user_role_check = $user->role == 'guest' ? 'guest' : 'admin';
        $text            = $request->image ? $request->imageInformation : $request->message;
        $create          = array(
            'text'  => $text,
            'image' => $request->image ? $request->image : null,
        );
        $user_chat       = $user->user_chats()->create($create);

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

    public function show(Request $request) {
        $user        = User::find($request->userId);
        $admin_chats = AdminChat::where('user_id', $user->id)->get();

        foreach ($admin_chats as $chattings) {
            $user->user_chats->push($chattings);
        }

        $html           = '';

        foreach ($user->user_chats->sortBy('created_at') as $chat) {
            if ($request->guest) {
                $text_align      = $chat->getTable() == 'user_chats' ? 'text-right' : 'text-left';
                $first_name      = $chat->getTable() == 'user_chats' ? 'admin' : $chat->user->first_name ;
                $last_name       = $chat->getTable() == 'user_chats' ? '' : $chat->user->last_name;
                $chat_msg        = $chat->getTable() == 'user_chats' ? 'chat-msg-user' : 'chat-msg-admin';
                $chat_msg_align  = $chat->getTable() == 'user_chats' ? 'chat-text-user' : 'chat-text-admin';
            }
            else {
                $text_align      = $chat->getTable() == 'user_chats' ? 'text-left' : 'text-right';
                $first_name      = $chat->getTable() == 'user_chats' ? $chat->user->first_name : 'Admin';
                $last_name       = $chat->getTable() == 'user_chats' ? $chat->user->last_name : '';
                $chat_msg        = $chat->getTable() == 'user_chats' ? 'chat-msg-admin' : 'chat-msg-user';
                $chat_msg_align  = $chat->getTable() == 'user_chats' ? 'chat-text-admin' : 'chat-text-user';
            }


            $html .= "<div class='mt-3'>";
            $html .= "<div class='$text_align'><small><span class='tbold'>$first_name $last_name </span>";
            $html .= $chat->created_at->isoFormat('dddd, D MMMM YYYY H:MM');
            $html .= " WIB</small></div>";
            $html .= "<div class='$chat_msg'>";
            $html .= "<div class='$chat_msg_align'>$chat->text</div>";
            $html .= "</div>";
            $html .= "</div>";
        }

        $userChatsHtml           = "<div class='mt-auto w-100'>$html</div>";

        return response()->json(compact('userChatsHtml'));
    }
}

