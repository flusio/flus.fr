<?php

namespace Website;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Router
{
    public static function loadApp(): \Minz\Router
    {
        $router = new \Minz\Router();

        $router->addRoute('GET', '/', 'Home#index', 'home');
        $router->addRoute('GET', '/projet', 'Home#project', 'project');
        $router->addRoute('GET', '/tarifs', 'Home#pricing', 'pricing');
        $router->addRoute('GET', '/visite', 'Home#tour', 'tour');
        $router->addRoute('GET', '/visite/:page', 'Home#tour', 'tour page');
        $router->addRoute('GET', '/financement', 'Home#funding', 'funding');
        $router->addRoute('GET', '/credits', 'Home#credits', 'credits');
        $router->addRoute('GET', '/mentions-legales', 'Home#legal', 'legal');
        $router->addRoute('GET', '/cgv', 'Home#cgv', 'cgv');
        $router->addRoute('GET', '/contact', 'Home#contact', 'contact');
        $router->addRoute('POST', '/contact', 'Home#sendContactEmail', 'send contact email');
        $router->addRoute('GET', '/securite', 'Home#security', 'security');
        $router->addRoute('GET', '/cagnotte', 'CommonPots#show', 'common pot');

        $router->addRoute('GET', '/robots.txt', 'Home#robots', 'robots.txt');
        $router->addRoute('GET', '/sitemap.xml', 'Home#sitemap', 'sitemap.xml');
        $router->addRoute('GET', '/.well-known/security.txt', 'Home#securityTxt', 'security.txt');

        $router->addRoute('GET', '/addons/updates.json', 'Addons#geckoUpdate');
        $router->addRoute('GET', '/addons/gecko/latest', 'Addons#geckoLatest');

        $router->addRoute('GET', '/account', 'Accounts#show', 'account');
        $router->addRoute('GET', '/account/login', 'Accounts#login', 'account login');
        $router->addRoute('POST', '/account/logout', 'Accounts#logout', 'account logout');
        $router->addRoute('POST', '/account/reminder', 'Accounts#setReminder', 'account set reminder');
        $router->addRoute('GET', '/account/address', 'Accounts#address', 'account address');
        $router->addRoute('POST', '/account/address', 'Accounts#updateAddress', 'account update address');
        $router->addRoute('GET', '/account/renew', 'Subscriptions#init', 'subscription init');
        $router->addRoute('POST', '/account/renew', 'Subscriptions#renew', 'subscription renew');
        $router->addRoute('GET', '/account/common-pot', 'CommonPots#show', 'common pot account');
        $router->addRoute(
            'GET',
            '/account/common-pot/contribute',
            'CommonPots#contribution',
            'common pot contribution'
        );
        $router->addRoute(
            'POST',
            '/account/common-pot/contribute',
            'CommonPots#contribute',
            'contribute common pot'
        );
        $router->addRoute('GET', '/account/common-pot/use', 'CommonPots#usage', 'common pot usage');
        $router->addRoute('POST', '/account/common-pot/use', 'CommonPots#use', 'use common pot');

        $router->addRoute('GET', '/payments/:id/pay', 'Payments#pay');
        $router->addRoute('GET', '/merci', 'Payments#succeeded');
        $router->addRoute('GET', '/annulation', 'Payments#canceled');

        $router->addRoute('GET', '/invoices/:id/pdf', 'Invoices#downloadPdf', 'invoice download pdf');

        $router->addRoute('GET', '/api/account', 'api/Accounts#show');
        $router->addRoute('GET', '/api/account/login-url', 'api/Accounts#loginUrl');
        $router->addRoute('GET', '/api/account/expired-at', 'api/Accounts#expiredAt');
        $router->addRoute('POST', '/api/accounts/sync', 'api/Accounts#sync');

        $router->addRoute('GET', '/admin', 'admin/Payments#index', 'admin');
        $router->addRoute('GET', '/admin/login', 'admin/Auth#login', 'login');
        $router->addRoute('POST', '/admin/login', 'admin/Auth#createSession', 'create_session');
        $router->addRoute('POST', '/admin/logout', 'admin/Auth#deleteSession', 'logout');
        $router->addRoute('GET', '/admin/credits/new', 'admin/Credits#init', 'new admin credit');
        $router->addRoute('POST', '/admin/credits/new', 'admin/Credits#create', 'create admin credit');
        $router->addRoute('GET', '/admin/payments/new', 'admin/Payments#init', 'new admin payment');
        $router->addRoute('POST', '/admin/payments/new', 'admin/Payments#create', 'create admin payment');
        $router->addRoute('GET', '/admin/payments/:id', 'admin/Payments#show', 'admin payment');
        $router->addRoute('POST', '/admin/payments/:id/confirm', 'admin/Payments#confirm', 'confirm admin payment');
        $router->addRoute('POST', '/admin/payments/:id/destroy', 'admin/Payments#destroy', 'destroy admin payment');
        $router->addRoute('GET', '/admin/accounts', 'admin/Accounts#index', 'admin accounts');
        $router->addRoute('GET', '/admin/accounts/:id', 'admin/Accounts#show', 'admin account');
        $router->addRoute('POST', '/admin/accounts/:id', 'admin/Accounts#update', 'update admin account');

        $router->addRoute('POST', '/stripe/hooks', 'Stripe#hooks');

        return $router;
    }

    public static function loadCli(): \Minz\Router
    {
        $router = self::loadApp();

        $router->addRoute('CLI', '/help', 'Help#show');

        $router->addRoute('CLI', '/jobs', 'Jobs#index');
        $router->addRoute('CLI', '/jobs/watch', 'Jobs#watch');
        $router->addRoute('CLI', '/jobs/run', 'Jobs#run');
        $router->addRoute('CLI', '/jobs/show', 'Jobs#show');
        $router->addRoute('CLI', '/jobs/unfail', 'Jobs#unfail');
        $router->addRoute('CLI', '/jobs/unlock', 'Jobs#unlock');

        $router->addRoute('CLI', '/migrations', 'Migrations#index');
        $router->addRoute('CLI', '/migrations/setup', 'Migrations#setup');
        $router->addRoute('CLI', '/migrations/rollback', 'Migrations#rollback');
        $router->addRoute('CLI', '/migrations/create', 'Migrations#create');

        $router->addRoute('CLI', '/accounts', 'Accounts#index');
        $router->addRoute('CLI', '/accounts/create', 'Accounts#create');
        $router->addRoute('CLI', '/accounts/login-url', 'Accounts#loginUrl');

        return $router;
    }
}
