<?php

namespace Website\admin;

use Website\utils;

class Auth
{
    /**
     * Show the admin login page
     *
     * Parameter is:
     *
     * - `from` (optional): allow to redirect to the given action pointer instead
     *   of the admin main page
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
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
     * Parameters are:
     *
     * - `csrf`
     * - `password`
     * - `from` (optional)
     *
     * If the password is good, user is redirected to the admin main page, or to
     * the `from` page.
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
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
     * Parameter is:
     *
     * - `csrf`
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response Always redirect to the home page
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
