<?php

namespace Website\controllers;

use Minz\Request;
use Minz\Response;
use Website\models;
use Website\services;
use Website\utils;

class CommonPots
{
    /**
     * Show the page about the common pot.
     *
     * @response 200
     */
    public function show(Request $request): Response
    {
        return Response::ok('common_pots/show.phtml', [
            'common_pot_amount' => models\PotUsage::findAvailableAmount(),
        ]);
    }
}
