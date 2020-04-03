<?php

namespace Website\controllers\home;

function index()
{
    return \Minz\Response::ok('home/index.phtml');
}
