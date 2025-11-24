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
        ini_set('intl.default_locale', 'fr');

        if ($request->method() === 'CLI') {
            $this->initCli($request);
        } else {
            $this->initApp($request);
        }

        $response = \Minz\Engine::run($request);

        if ($response instanceof \Minz\Response) {
            $response->setHeader('Permissions-Policy', 'interest-cohort=()'); // @see https://cleanuptheweb.org/
            $response->setHeader('Referrer-Policy', 'same-origin');
            $response->setHeader('X-Content-Type-Options', 'nosniff');
            $response->setHeader('X-Frame-Options', 'deny');

            $plausible_url = \Minz\Configuration::$application['plausible_url'];
            if ($plausible_url) {
                $response->addContentSecurityPolicy('connect-src', "'self' {$plausible_url}");
                $response->addContentSecurityPolicy('script-src', "'self' {$plausible_url}");
            }
        }

        return $response;
    }

    private function initApp(Request $request): void
    {
        $router = Router::loadApp();

        \Minz\Engine::init($router, [
            'start_session' => \Minz\Configuration::$environment !== 'test',
            'not_found_template' => 'not_found.phtml',
            'internal_server_error_template' => 'internal_server_error.phtml',
            'controller_namespace' => '\\Website\\controllers',
        ]);

        \Minz\Template\Simple::addGlobals([
            'environment' => \Minz\Configuration::$environment,
            'csrf_token' => \Website\Csrf::generate(),
            'errors' => [],
            'error' => null,
            'load_form_statics' => false,
            'current_user' => auth\CurrentUser::get(),
            'current_account' => auth\CurrentUser::getAccount(),
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
            'not_found_template' => 'cli/not_found.txt',
            'internal_server_error_template' => 'cli/internal_server_error.txt',
            'controller_namespace' => '\\Website\\cli',
        ]);

        $bin = $request->parameters->getString('bin');
        $bin = $bin === 'cli' ? 'php cli' : $bin;

        $current_command = $request->path();
        $current_command = trim(str_replace('/', ' ', $current_command));

        \Minz\Template\Simple::addGlobals([
            'environment' => \Minz\Configuration::$environment,
            'errors' => [],
            'error' => null,
            'bin' => $bin,
            'current_command' => $current_command,
            'support_email' => \Minz\Configuration::$application['support_email'],
        ]);
    }
}
