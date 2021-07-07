<?php

namespace App\Http\Controllers;

use App\Models\{User, AdminChat};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, DB};

class AdminChatController extends Controller
{
    public function store(Request $request)
    {
        $user   = User::find($request->userId);
        $admin  = Auth::user();
        $text   = $request->image ? $request->imageInformation : $request->message;
        $create = array(
            'text'  => $text,
            'image' => $request->image ? $request->image : null,
        );

        $admin_chat = $user->admin_chats()->create($create);
        $user_chat  = $user->user_chats;

        $iso_format_chat = $admin_chat->created_at->isoFormat('dddd, D MMMM YYYY H:MM');

        $chat  = "<div class='mt-4'>";
        $chat .= "<div class='text-right'><small><span class='tbold'>Anda </span>$iso_format_chat, WIB</small></div>";
        $chat .= "<div class='chat-msg-user'>";
        $chat .= "<div class='chat-text-user'>$request->message</div>";
        $chat .= "</div>";
        $chat .= "</div>";

        return response()->json(compact('chat'));
    }
}
