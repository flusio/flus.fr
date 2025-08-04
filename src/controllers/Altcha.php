<?php

namespace Website\controllers;

use AltchaOrg;
use Minz\Request;
use Minz\Response;

class Altcha
{
    public function show(Request $request): Response
    {
        $altcha = new AltchaOrg\Altcha\Altcha(\Minz\Configuration::$secret_key);
        $challenge_options = new AltchaOrg\Altcha\ChallengeOptions(
            maxNumber: 500000,
            expires: \Minz\Time::fromNow(20, 'minutes'),
        );

        try {
            $challenge = $altcha->createChallenge($challenge_options);
        } catch (\Exception $e) {
            return Response::internalServerError('internal_server_error.phtml', [
                'error' => $e->getMessage(),
            ]);
        }

        $challenge_json = json_encode($challenge);

        if ($challenge_json === false) {
            return Response::internalServerError('internal_server_error.phtml', [
                'error' => 'Cannot encode the Altcha challenge',
            ]);
        }

        $response = Response::text(200, $challenge_json);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }
}
