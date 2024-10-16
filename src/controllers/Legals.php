<?php

namespace Website\controllers;

use Minz\Request;
use Minz\Response;

class Legals
{
    public function index(Request $request): Response
    {
        return Response::ok('legals/index.phtml');
    }

    public function notices(Request $request): Response
    {
        return Response::ok('legals/notices.phtml');
    }

    public function generalTerms(Request $request): Response
    {
        return Response::ok('legals/general_terms.phtml');
    }

    public function privacyPolicy(Request $request): Response
    {
        return Response::ok('legals/privacy_policy.phtml');
    }

    public function cookiesPolicy(Request $request): Response
    {
        return Response::ok('legals/cookies_policy.phtml');
    }

    public function cgv(Request $request): Response
    {
        return Response::ok('legals/cgv.phtml');
    }
}
