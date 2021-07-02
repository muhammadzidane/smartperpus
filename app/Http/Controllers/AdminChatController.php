<?php

namespace App\Http\Controllers;

use App\Models\ { User, UserChat };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
