<?php

namespace Website\utils;

function currentUser()
{
    if (!isset($_SESSION['connected']) || !$_SESSION['connected']) {
        return null;
    }

    return [
        'username' => 'The administrator',
    ];
}
