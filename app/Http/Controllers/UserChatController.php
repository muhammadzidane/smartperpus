<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\{UserChat, AdminChat};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage, Validator};

class UserChatController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->ajax() && $request->photo) {
            // Validasi backend
            $validator = Validator::make(
                $request->all(),
                array(
                    'photo' => array('nullable', 'file', 'mimes:jpg,png,jpeg'),
                )
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
        } else {
            $user            = User::find(Auth::id());
            $user_role_check = $user->role == 'guest' ? 'guest' : 'admin';
            $photo_name      = $request->photo ? $request->photo->getClientOriginalName() : '';
            $text            = $request->message ? $request->message : null;
            $create          = array(
                'text' => $text,
                'image' => $request->photo ? $photo_name : null,
            );

            $user_chat  = $user->user_chats()->create($create);

            if ($request->photo && !Storage::exists('public/chats/' . $photo_name)) {
                $request->photo->storeAs('public/chats', str_replace(' ', '_', strtolower($photo_name)));
            }

            $chat = '';
            $chat .= "<div class='mt-3'>";
            $chat .= "<div class='text-right'>";
            $chat .= "<small>";
            $chat .= $user_chat->created_at->isoFormat('dddd, D MMMM YYYY H:m');
            $chat .= "</small>";
            $chat .= "</div>";

            if ($user_chat->image) {
                $chat .= "<div class='chat-img-user'>";
                $chat .= "<img class='w-100 d-block mb-3' src='" . asset('storage/chats/' . $user_chat->image) . "'>";

                if ($user_chat->text != null) {
                    $chat .= "<div class='chat-text-user'>$user_chat->text</div>";
                }

                $chat .= "</div>";
            } else {
                $chat .= "<div class='chat-msg-user'><div class='chat-text-user'>$user_chat->text</div></div>";
            }

            $chat .= '</div>';

            return response()->json(compact('chat'));
        }
    }

    public function show(Request $request)
    {
        $user        = User::find($request->userId);
        $admin_chats = AdminChat::where('user_id', $user->id)->get();

        foreach ($admin_chats as $chattings) {
            $user->user_chats->push($chattings);
        }

        $html           = '';

        foreach ($user->user_chats->sortBy('created_at') as $chat) {
            if ($request->guest) {
                $text_align      = $chat->getTable() == 'user_chats' ? 'text-right' : 'text-left';
                $first_name      = $chat->getTable() == 'user_chats' ? 'admin' : $chat->user->first_name;
                $last_name       = $chat->getTable() == 'user_chats' ? '' : $chat->user->last_name;
                $chat_msg        = $chat->getTable() == 'user_chats' ? 'chat-msg-user' : 'chat-msg-admin';
                $chat_msg_align  = $chat->getTable() == 'user_chats' ? 'chat-text-user' : 'chat-text-admin';
            } else {
                $text_align      = $chat->getTable() == 'user_chats' ? 'text-left' : 'text-right';
                $first_name      = $chat->getTable() == 'user_chats' ? $chat->user->first_name : 'Admin';
                $last_name       = $chat->getTable() == 'user_chats' ? $chat->user->last_name : '';
                $chat_msg        = $chat->getTable() == 'user_chats' ? 'chat-msg-admin' : 'chat-msg-user';
                $chat_msg_align  = $chat->getTable() == 'user_chats' ? 'chat-text-admin' : 'chat-text-user';
            }


            $html .= "<div class='mt-3'>";
            $html .= "<div class='text-right'>";
            $html .= "<small>";
            $html .= $chat->created_at->isoFormat('dddd, D MMMM YYYY H:m');
            $html .= "</small>";
            $html .= "</div>";

            if ($chat->image) {
                $html .= "<div class='chat-img-user'>";
                $html .= "<img class='w-100 d-block mb-3' src='" . asset('storage/chats/' . $chat->image) . "'>";

                if ($chat->text != null) {
                    $html .= "<div class='chat-text-user'>$chat->text</div>";
                }

                $html .= "</div>";
            } else {
                $html .= "<div class='chat-msg-user'><div class='chat-text-user'>$chat->text</div></div>";
            }

            $html .= '</div>';
        }

        $userChatsHtml           = "<div class='mt-auto w-100'>$html</div>";

        return response()->json(compact('userChatsHtml'));
    }
}
