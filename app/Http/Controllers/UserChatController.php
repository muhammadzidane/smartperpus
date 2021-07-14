<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\{UserChat, AdminChat};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Storage, Validator, DB};

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

            if ($user->role == 'guest') {
                $photo_name = $request->photo ? $request->photo->getClientOriginalName() : '';
                $text       = $request->message ? $request->message : null;
                $create     = array(
                    'text'  => $text,
                    'image' => $request->photo ? $photo_name : null,
                );
            } else { // Admin || Super admin
                $user       = User::find($request->userId);
                $photo_name = $request->photo ? $request->photo->getClientOriginalName() : '';

                $text       = $request->message ? $request->message : null;
                $create     = array(
                    'text'  => $text,
                    'image' => $request->photo ? $photo_name : null,
                );

                $admin_chat = $user->admin_chats()->create($create);
                $user       = User::find(Auth::id());
                $user_chat  = $user->user_chats;
            }

            if ($user->role == 'guest') {
                $user_chat  = $user->user_chats()->create($create);
            } else {
                $user_chat  = $admin_chat;
            }

            if ($request->photo && !Storage::exists('public/chats/' . $photo_name)) {
                $request->photo->storeAs('public/chats', $photo_name);
            }

            $chat = '';
            $chat .= "<div class='mt-4'>";
            $chat .= "<div class='text-right'>";
            $chat .= "<small>";
            $chat .= $user_chat->created_at->isoFormat('dddd, D MMMM YYYY H:m');
            $chat .= "</small>";
            $chat .= "</div>";

            if ($request->photo !== null) {
                $chat .= "<div class='chat-img-user'>";
                $chat .= "<img class='w-100 d-block mb-3' src='" . asset('storage/chats/' . $user_chat->image) . "'>";

                if ($user_chat->text !== null) {
                    $chat .= "<div class='chat-text-user'>$user_chat->text</div>";
                }

                $chat .= "</div>";
            } else {
                $chat .= "<div class='chat-msg-user'><div class='chat-text-user'>$user_chat->text</div></div>";
            }

            $chat .= '</div>';

            // Flag apakah ada request untuk file gambar
            $image = $request->photo ? true : false;

            return response()->json(compact('chat', 'image'));
        }
    }

    public function show(Request $request)
    {
        // Jika mengakses route secara langsung di web maka redirect ke halaman home
        if (!$request->userId && !$request->ajax()) {
            return redirect()->route('home');
        }

        $user        = User::find($request->userId);
        $admin_chats = AdminChat::where('user_id', $user->id)->get();

        foreach ($admin_chats as $chattings) {
            $user->user_chats->push($chattings);
        }

        if ($request->adminClickShow) {
            $update = array('read' => true);

            $user->user_chats()->update($update);
        }

        $html           = '';

        foreach ($user->user_chats->sortBy('created_at') as $chat) {
            $deleted_chat   = $chat->deleted_at !== null ? 'text-grey' : '';
            $auth_role      = Auth::user()->role;
            $table_check    = $chat->getTable() !== 'user_chats';
            $text_align     = $table_check ? 'text-right' : ($auth_role === 'guest' ? 'text-right' : 'text-left');
            $first_name     = $table_check ? 'Admin, ' : ($auth_role !== 'guest' ? $chat->user->first_name : '');
            $last_name      = $table_check ? '' : ($auth_role !== 'guest' ? $chat->user->last_name : '');
            $chat_msg       = $table_check ? 'chat-msg-user' : ($auth_role !== 'guest' ? 'chat-msg-admin' : '');
            $chat_img       = $table_check ? 'chat-img-user' : ($auth_role !== 'guest' ? 'chat-img-admin' : 'chat-img-user');
            $chat_msg_align = $table_check ? 'chat-text-user' : 'chat-text-admin';

            $html .= "<div class='mt-4'>";
            $html .= "<div class='$text_align $deleted_chat'>";
            $html .= "<small>";
            $html .= $table_check ? '' : "<span class='tbold'>$first_name $last_name </span>";
            $html .= $chat->created_at->isoFormat('dddd, D MMMM YYYY H:m');
            $html .= "</small>";
            $html .= "</div>";

            if ($chat->image) {
                $html .= "<div class='$chat_img'>";
                $html .= "<img class='w-100 d-block mb-3' src='" . asset('storage/chats/' . $chat->image) . "'>";

                if ($chat->text != null) {
                    $html .= "<div class='$chat_msg_align'>$chat->text</div>";
                }

                $html .= "</div>";
            } else {
                $html .= "<div class='$chat_msg'><div class='chat-text-user'>$chat->text</div></div>";
            }

            $html .= '</div>';
        }

        $userChatsHtml = $html;

        return response()->json(compact('userChatsHtml'));
    } // End Show

    // Update untuk notifikasi chatting
    public function update(Request $request)
    {
        $update      = array('read' => true);

        User::find($request->userId)->admin_chats()->update($update);
    }
    // End Update

    public function search(Request $request)
    {
        $query  = '';
        $query .= " SELECT user_chats.* FROM user_chats INNER JOIN users ON user_chats.user_id=users.id,";
        $query .= ' (SELECT user_id,max(created_at) AS transaction_date FROM user_chats GROUP BY user_id) max_user';
        $query .= ' WHERE user_chats.user_id = max_user.user_id';
        $query .= " AND user_chats.created_at = max_user.transaction_date";
        $query .= " AND concat_ws(' ', users.first_name, users.last_name)";
        $query .= "  LIKE '%$request->searchVal%' ORDER BY user_chats.created_at DESC";

        $chats = DB::select($query);

        $userChatHtml  = "";

        foreach ($chats as $chat) {
            $userChatHtml .= "<div class='user-chat pl-3 pr-2 py-2' data-id='" . User::find($chat->user_id)->id . "'>";
            $userChatHtml .= "<div class='d-flex justify-content-between'>";
            $userChatHtml .= "<div class='tbold text-grey'>" . User::find($chat->user_id)->first_name . ' ';
            $userChatHtml .= User::find($chat->user_id)->last_name . "</div>";
            $userChatHtml .= "<div class='user-chat-time'>";

            if (Carbon::now()->diffInDays(Carbon::parse($chat->created_at)) >= 1) {
                $userChatHtml .= "<small>" . Carbon::parse($chat->created_at)->format('y/m/d') . "</small>";
            } else {
                $userChatHtml .= "<small>" . Carbon::parse($chat->created_at)->format('H:i') . "</small>";
            }

            $userChatHtml .= "</div>";
            $userChatHtml .= "</div>";
            $userChatHtml .= "<div>";
            $userChatHtml .= "<span id='user-chats-text'>" . strlen($chat->text) <= 18 ? $chat->text : substr($chat->text, 1, 18);
            $userChatHtml .= $chat->text >= 18 ? '...' . "</span>" : "</span>";

            if (UserChat::where('user_id', $chat->user_id)->where('read', false)->get()->count() !== 0) {
                $userChatHtml .= "<span class='user-chat-notifications'>";
                $userChatHtml .= "<small>" . UserChat::where('user_id', $chat->user_id)->where('read', false)->get()->count();
                $userChatHtml .= "</small>";
                $userChatHtml .= "</span>";
            }
            $userChatHtml .= "</div>";
            $userChatHtml .= "</div>";
        }

        return response()->json(compact('userChatHtml'));
    }
    // End Search

    public function destroy(User $userChat)
    {
        $user = Auth::user();

        if ($user->role != "guest") {
            $userChat->user_chats()->forceDelete();
            $userChat->admin_chats()->forceDelete();
        } else {
            $userChat->user_chats()->delete();
            $userChat->admin_chats()->delete();
        }

        return response()->json()->status();
    }
}
