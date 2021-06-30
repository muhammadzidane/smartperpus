<div class="mt-3">
    <div class="text-right">
        <small>
            <span class="tbold">{{  }},</span>
            <span> {{ $chat->created_at->isoFormat('dddd, D MMMM YYYY H:MM') }} WIB</span>
        </small>
    </div>
    <div class="chat-msg-user">
        <div class="chat-text-user">{{ $chat->text }}</div>
    </div>
</div>
