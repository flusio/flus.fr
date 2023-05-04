<?php

namespace Website\controllers\admin;

use Minz\Request;
use Minz\Response;
use Website\utils;

class Auth
{
    /**
     * Show the admin login page
     *
     * @request_param string from
     *     To redirect to the given action pointer after the connection
     *     (optional)
     *
     * @response 302 /admin
     *     If user is already connected as an admin
     * @response 200
     *     On success
     */
    public function login(Request $request): Response
    {
        if (utils\CurrentUser::isAdmin()) {
            return Response::redirect('admin');
        }

        return Response::ok('admin/auth/login.phtml', [
            'from' => $request->param('from'),
        ]);
    }

    /**
     * Create a session for the user who tries to log in
     *
     * @request_param string password
     * @request_param string csrf
     * @request_param string from
     *     To redirect to the given action pointer after the connection,
     *     default is `admin`
     *
     * @response 302 /admin
     *     If user is already connected as an admin
     * @response 400
     *     If CSRF or password is invalid
     * @response 302 :from
     *     On success
     */
    public function createSession(Request $request): Response
    {
        if (utils\CurrentUser::isAdmin()) {
            return Response::redirect('admin');
        }

        $password = $request->param('password');
        $from = $request->param('from');

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return Response::badRequest('admin/auth/login.phtml', [
                'from' => $from,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $hash = \Minz\Configuration::$application['admin_secret'];
        if (\password_verify($password, $hash)) {
            utils\CurrentUser::logAdminIn();

            $location = '';
            if ($from) {
                $location = urldecode($from);
            }

            if (!$location) {
                $location = 'admin';
            }

            return Response::redirect($location, ['status' => 'connected']);
        } else {
            return Response::badRequest('admin/auth/login.phtml', [
                'from' => $from,
                'error' => 'Le mot de passe semble invalide, désolé.',
            ]);
        }
    }

    /**
     * Delete a session and log out the user
     *
     * @request_param string csrf
     *
     * @response 302 /
     */
    public function deleteSession(Request $request): Response
    {
        if (\Minz\Csrf::validate($request->param('csrf')) && utils\CurrentUser::isAdmin()) {
            utils\CurrentUser::logOut();
        }

        return Response::redirect('home');
    }
}
