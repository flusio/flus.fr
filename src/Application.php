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
        $router->addRoute('get', '/', 'Home#index', 'home');
        $router->addRoute('get', '/financement', 'Home#funding', 'funding');
        $router->addRoute('get', '/credits', 'Home#credits', 'credits');
        $router->addRoute('get', '/mentions-legales', 'Home#legal', 'legal');
        $router->addRoute('get', '/cgv', 'Home#cgv', 'cgv');
        $router->addRoute('get', '/cagnotte', 'Payments#init', 'common pot');
        $router->addRoute('post', '/cagnotte', 'Payments#payCommonPot', 'submit common pot');
        $router->addRoute('get', '/merci', 'Payments#succeeded');
        $router->addRoute('get', '/annulation', 'Payments#canceled');

        $router->addRoute('get', '/robots.txt', 'Home#robots', 'robots.txt');
        $router->addRoute('get', '/sitemap.xml', 'Home#sitemap', 'sitemap.xml');

        $router->addRoute('get', '/payments/:id', 'Payments#show');
        $router->addRoute('get', '/payments/:id/pay', 'Payments#pay');
        $router->addRoute('post', '/payments/subscriptions', 'Payments#paySubscription');
        $router->addRoute('get', '/invoices/pdf/:id', 'Invoices#downloadPdf');

        $router->addRoute('get', '/admin', 'admin/Payments#index', 'admin');
        $router->addRoute('get', '/admin/login', 'admin/Auth#login', 'login');
        $router->addRoute('post', '/admin/login', 'admin/Auth#createSession', 'create_session');
        $router->addRoute('post', '/admin/logout', 'admin/Auth#deleteSession', 'logout');
        $router->addRoute('get', '/admin/payments/new', 'admin/Payments#init', 'new admin payment');
        $router->addRoute('post', '/admin/payments/new', 'admin/Payments#create', 'create admin payment');
        $router->addRoute('get', '/admin/payments/:id', 'admin/Payments#show', 'admin payment');
        $router->addRoute('post', '/admin/payments/:id/complete', 'admin/Payments#complete', 'complete admin payment');
        $router->addRoute('post', '/admin/payments/:id/destroy', 'admin/Payments#destroy', 'destroy admin payment');
        $router->addRoute('get', '/admin/invoices/pdf/:id', 'Invoices#downloadPdf', 'download_pdf_from_admin');

        $router->addRoute('post', '/stripe/hooks', 'Stripe#hooks');

        $router->addRoute('cli', '/system/init', 'System#init');
        $router->addRoute('cli', '/system/migrate', 'System#migrate');
        $router->addRoute('cli', '/invoices/:id/email', 'Invoices#sendPdf');

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
