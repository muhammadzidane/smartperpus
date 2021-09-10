<?php

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
