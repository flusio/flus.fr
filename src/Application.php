<?php

namespace Website;

class Application
{
    /** @var \Minz\Engine **/
    private $engine;

    public function __construct()
    {
        include_once('utils/application.php');
        include_once('utils/view_helpers.php');

        // Initialize the routes
        $router = new \Minz\Router();
        $router->addRoute('get', '/', 'home#index');
        $router->addRoute('get', '/financement', 'home#funding');
        $router->addRoute('get', '/credits', 'home#credits');
        $router->addRoute('get', '/mentions-legales', 'home#legal');
        $router->addRoute('get', '/cgv', 'home#cgv');
        $router->addRoute('get', '/cagnotte', 'payments#init');
        $router->addRoute('post', '/cagnotte', 'payments#payCommonPot');
        $router->addRoute('get', '/merci', 'payments#succeeded');
        $router->addRoute('get', '/annulation', 'payments#canceled');

        $router->addRoute('get', '/robots.txt', 'home#robots');
        $router->addRoute('get', '/sitemap.xml', 'home#sitemap');

        $router->addRoute('get', '/payments/:id', 'payments#show');
        $router->addRoute('get', '/payments/:id/pay', 'payments#pay');
        $router->addRoute('post', '/payments/subscriptions', 'payments#paySubscription');
        $router->addRoute('get', '/invoices/pdf/:id', 'invoices#download_pdf');

        $router->addRoute('get', '/admin', 'admin/payments#index', 'admin');
        $router->addRoute('get', '/admin/login', 'admin/auth#login', 'login');
        $router->addRoute('post', '/admin/login', 'admin/auth#create_session', 'create_session');
        $router->addRoute('post', '/admin/logout', 'admin/auth#delete_session', 'logout');
        $router->addRoute('get', '/admin/invoices/pdf/:id', 'invoices#download_pdf', 'download_pdf_from_admin');

        $router->addRoute('post', '/stripe/hooks', 'stripe#hooks');

        $router->addRoute('cli', '/system/init', 'system#init');
        $router->addRoute('cli', '/system/migrate', 'system#migrate');
        $router->addRoute('cli', '/invoices/:id/email', 'invoices#send_pdf');

        $this->engine = new \Minz\Engine($router);
        \Minz\Url::setRouter($router);

        setlocale(LC_ALL, 'fr_FR.UTF8');
    }

    public function run($request)
    {
        \Minz\Output\View::declareDefaultVariables([
            'environment' => \Minz\Configuration::$environment,
            'errors' => [],
            'error' => null,
            'load_form_statics' => false,
            'current_user' => utils\currentUser(),
        ]);

        return $this->engine->run($request);
    }
}
