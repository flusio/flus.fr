<?php

namespace Website;

class Application
{
    /** @var \Minz\Engine **/
    private $engine;

    public function __construct()
    {
        include_once('utils/view_helpers.php');

        // Initialize the routes
        $router = new \Minz\Router();
        $router->addRoute('get', '/', 'Home#index', 'home');
        $router->addRoute('get', '/financement', 'Home#funding', 'funding');
        $router->addRoute('get', '/credits', 'Home#credits', 'credits');
        $router->addRoute('get', '/mentions-legales', 'Home#legal', 'legal');
        $router->addRoute('get', '/cgv', 'Home#cgv', 'cgv');
        $router->addRoute('get', '/contact', 'Home#contact', 'contact');
        $router->addRoute('post', '/contact', 'Home#sendContactEmail', 'send contact email');
        $router->addRoute('get', '/cagnotte', 'Payments#init', 'common pot');
        $router->addRoute('post', '/cagnotte', 'Payments#payCommonPot', 'submit common pot');
        $router->addRoute('get', '/merci', 'Payments#succeeded');
        $router->addRoute('get', '/annulation', 'Payments#canceled');

        $router->addRoute('get', '/robots.txt', 'Home#robots', 'robots.txt');
        $router->addRoute('get', '/sitemap.xml', 'Home#sitemap', 'sitemap.xml');

        $router->addRoute('get', '/account', 'Accounts#show', 'account');
        $router->addRoute('get', '/account/login', 'Accounts#login', 'account login');
        $router->addRoute('get', '/account/address', 'Accounts#address', 'account address');
        $router->addRoute('post', '/account/address', 'Accounts#updateAddress', 'account update address');
        $router->addRoute('get', '/account/renew', 'Subscriptions#init', 'subscription init');
        $router->addRoute('post', '/account/renew', 'Subscriptions#renew', 'subscription renew');
        $router->addRoute('get', '/account/invoices/pdf/:id', 'api/Invoices#downloadPdf', 'account download pdf');

        $router->addRoute('get', '/payments/:id/pay', 'Payments#pay');

        $router->addRoute('get', '/api/account', 'api/Accounts#show');
        $router->addRoute('get', '/api/account/login-url', 'api/Accounts#loginUrl');
        $router->addRoute('get', '/api/account/expired-at', 'api/Accounts#expiredAt');

        $router->addRoute('get', '/api/payments/:id', 'api/Payments#show');
        $router->addRoute('post', '/api/payments/subscriptions', 'api/Payments#paySubscription');

        $router->addRoute('get', '/api/invoices/pdf/:id', 'api/Invoices#downloadPdf');

        $router->addRoute('get', '/admin', 'admin/Payments#index', 'admin');
        $router->addRoute('get', '/admin/login', 'admin/Auth#login', 'login');
        $router->addRoute('post', '/admin/login', 'admin/Auth#createSession', 'create_session');
        $router->addRoute('post', '/admin/logout', 'admin/Auth#deleteSession', 'logout');
        $router->addRoute('get', '/admin/payments/new', 'admin/Payments#init', 'new admin payment');
        $router->addRoute('post', '/admin/payments/new', 'admin/Payments#create', 'create admin payment');
        $router->addRoute('get', '/admin/payments/:id', 'admin/Payments#show', 'admin payment');
        $router->addRoute('post', '/admin/payments/:id/complete', 'admin/Payments#complete', 'complete admin payment');
        $router->addRoute('post', '/admin/payments/:id/destroy', 'admin/Payments#destroy', 'destroy admin payment');
        $router->addRoute('get', '/admin/invoices/pdf/:id', 'api/Invoices#downloadPdf', 'download_pdf_from_admin');

        $router->addRoute('post', '/stripe/hooks', 'Stripe#hooks');

        $router->addRoute('cli', '/system/init', 'cli/System#init');
        $router->addRoute('cli', '/system/migrate', 'cli/System#migrate');
        $router->addRoute('cli', '/system/rollback', 'cli/System#rollback');
        $router->addRoute('cli', '/payments/complete', 'cli/Payments#complete');
        $router->addRoute('cli', '/invoices/:id/email', 'cli/Invoices#sendPdf');
        $router->addRoute('cli', '/accounts', 'cli/Accounts#index');
        $router->addRoute('cli', '/accounts/create', 'cli/Accounts#create');
        $router->addRoute('cli', '/accounts/login-url', 'cli/Accounts#loginUrl');

        // TODO The following routes are deprecated and will be removed in the future.
        $router->addRoute('get', '/payments/:id', 'api/Payments#show');
        $router->addRoute('post', '/payments/subscriptions', 'api/Payments#paySubscription');
        $router->addRoute('get', '/invoices/pdf/:id', 'api/Invoices#downloadPdf');

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
            'current_user' => utils\CurrentUser::get(),
        ]);

        return $this->engine->run($request, [
            'not_found_view_pointer' => 'not_found.phtml',
            'internal_server_error_view_pointer' => 'internal_server_error.phtml',
        ]);
    }
}
