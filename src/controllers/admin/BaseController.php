<?php

namespace Website\controllers\admin;

use Minz\Controller;
use Minz\Request;
use Minz\Response;
use Website\auth;

/**
 * @author  Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class BaseController
{
    #[Controller\ErrorHandler(auth\NotAdminError::class)]
    public function redirectOnNotAdminError(
        Request $request,
        auth\NotAdminError $error,
    ): Response {
        return Response::redirect('login');
    }
}
