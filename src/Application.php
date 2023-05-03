<?php

namespace Website;

class Application
{
    public function run($request)
    {
        include_once('utils/view_helpers.php');

        setlocale(LC_ALL, 'fr_FR.UTF8');

        $router = new Router();

        \Minz\Engine::init($router, [
            'start_session' => \Minz\Configuration::$environment !== 'test',
            'not_found_view_pointer' => 'not_found.phtml',
            'internal_server_error_view_pointer' => 'internal_server_error.phtml',
            'controller_namespace' => '\\Website\\controllers',
        ]);

        \Minz\Output\View::declareDefaultVariables([
            'environment' => \Minz\Configuration::$environment,
            'csrf_token' => \Minz\Csrf::generate(),
            'errors' => [],
            'error' => null,
            'load_form_statics' => false,
            'current_user' => utils\CurrentUser::get(),
            'current_page' => null,
        ]);

        return \Minz\Engine::run($request);
    }
}
