<?php

namespace Website\controllers\admin;

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
    public function login($request)
    {
        if (utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('admin');
        }

        return \Minz\Response::ok('admin/auth/login.phtml', [
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
    public function createSession($request)
    {
        if (utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('admin');
        }

        $password = $request->param('password');
        $from = $request->param('from');

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('admin/auth/login.phtml', [
                'from' => $from,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $hash = \Minz\Configuration::$application['admin_secret'];
        if (\password_verify($password, $hash)) {
            utils\CurrentUser::logAdminIn();

            if ($from) {
                $location = urldecode($from);
            } else {
                $location = 'admin';
            }
            return \Minz\Response::redirect($location, ['status' => 'connected']);
        } else {
            return \Minz\Response::badRequest('admin/auth/login.phtml', [
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
    public function deleteSession($request)
    {
        $csrf = new \Minz\CSRF();
        if ($csrf->validateToken($request->param('csrf')) && utils\CurrentUser::isAdmin()) {
            utils\CurrentUser::logOut();
        }

        return \Minz\Response::redirect('home');
    }
}
