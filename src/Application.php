<?php

namespace Website;

use Minz\Request;
use Minz\Response;

/**
 * @phpstan-import-type ResponseReturnable from Response
 */
class Application
{
    /**
     * @return ResponseReturnable
     */
    public function run(Request $request): mixed
    {
        setlocale(LC_ALL, 'fr_FR.UTF8');

        if ($request->method() === 'CLI') {
            $this->initCli($request);
        } else {
            $this->initApp($request);
        }

        return \Minz\Engine::run($request);
    }

    private function initApp(Request $request): void
    {
        include_once('utils/view_helpers.php');

        $router = Router::loadApp();

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
            'current_account' => utils\CurrentUser::getAccount(),
            'current_page' => null,
            'plausible_url' => \Minz\Configuration::$application['plausible_url'],
            'current_host' => \Minz\Configuration::$url_options['host'],
            'support_email' => \Minz\Configuration::$application['support_email'],
        ]);
    }

    private function initCli(Request $request): void
    {
        $router = Router::loadCli();

        \Minz\Engine::init($router, [
            'not_found_view_pointer' => 'cli/not_found.txt',
            'internal_server_error_view_pointer' => 'cli/internal_server_error.txt',
            'controller_namespace' => '\\Website\\cli',
        ]);

        $bin = $request->param('bin');
        $bin = $bin === 'cli' ? 'php cli' : $bin;

        $current_command = $request->path();
        $current_command = trim(str_replace('/', ' ', $current_command));

        \Minz\Output\View::declareDefaultVariables([
            'environment' => \Minz\Configuration::$environment,
            'errors' => [],
            'error' => null,
            'bin' => $bin,
            'current_command' => $current_command,
            'support_email' => \Minz\Configuration::$application['support_email'],
        ]);
    }
}
