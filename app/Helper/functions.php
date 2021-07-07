<?php

use Illuminate\Support\Facades\Auth;
use App\Models\{UserChat, AdminChat};

function rupiah_format($value)
{
    return 'Rp' . number_format($value, 0, 0, '.');
}

function getChattingNotification($user)
{
    if ($user == 'guest') {
        // return UserChat::
    }
}
