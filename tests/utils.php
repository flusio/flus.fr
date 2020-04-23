<?php

namespace Website\tests\utils;

function login()
{
    $_SESSION['connected'] = true;
}

function logout()
{
    session_unset();
}
