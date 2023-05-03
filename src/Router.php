<?php

namespace Website;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Router extends \Minz\Router
{
    public function __construct()
    {
        parent::__construct();

        $this->addRoute('GET', '/', 'Home#index', 'home');
        $this->addRoute('GET', '/projet', 'Home#project', 'project');
        $this->addRoute('GET', '/tarifs', 'Home#pricing', 'pricing');
        $this->addRoute('GET', '/visite', 'Home#tour', 'tour');
        $this->addRoute('GET', '/visite/:page', 'Home#tour', 'tour page');
        $this->addRoute('GET', '/financement', 'Home#funding', 'funding');
        $this->addRoute('GET', '/credits', 'Home#credits', 'credits');
        $this->addRoute('GET', '/mentions-legales', 'Home#legal', 'legal');
        $this->addRoute('GET', '/cgv', 'Home#cgv', 'cgv');
        $this->addRoute('GET', '/contact', 'Home#contact', 'contact');
        $this->addRoute('POST', '/contact', 'Home#sendContactEmail', 'send contact email');
        $this->addRoute('GET', '/securite', 'Home#security', 'security');
        $this->addRoute('GET', '/cagnotte', 'CommonPots#show', 'common pot');

        $this->addRoute('GET', '/robots.txt', 'Home#robots', 'robots.txt');
        $this->addRoute('GET', '/sitemap.xml', 'Home#sitemap', 'sitemap.xml');
        $this->addRoute('GET', '/.well-known/security.txt', 'Home#securityTxt', 'security.txt');

        $this->addRoute('GET', '/addons/updates.json', 'Addons#geckoUpdate');
        $this->addRoute('GET', '/addons/gecko/latest', 'Addons#geckoLatest');

        $this->addRoute('GET', '/account', 'Accounts#show', 'account');
        $this->addRoute('GET', '/account/login', 'Accounts#login', 'account login');
        $this->addRoute('POST', '/account/logout', 'Accounts#logout', 'account logout');
        $this->addRoute('POST', '/account/reminder', 'Accounts#setReminder', 'account set reminder');
        $this->addRoute('GET', '/account/address', 'Accounts#address', 'account address');
        $this->addRoute('POST', '/account/address', 'Accounts#updateAddress', 'account update address');
        $this->addRoute('GET', '/account/renew', 'Subscriptions#init', 'subscription init');
        $this->addRoute('POST', '/account/renew', 'Subscriptions#renew', 'subscription renew');
        $this->addRoute('GET', '/account/common-pot', 'CommonPots#show', 'common pot account');
        $this->addRoute(
            'GET',
            '/account/common-pot/contribute',
            'CommonPots#contribution',
            'common pot contribution'
        );
        $this->addRoute(
            'POST',
            '/account/common-pot/contribute',
            'CommonPots#contribute',
            'contribute common pot'
        );
        $this->addRoute('GET', '/account/common-pot/use', 'CommonPots#usage', 'common pot usage');
        $this->addRoute('POST', '/account/common-pot/use', 'CommonPots#use', 'use common pot');

        $this->addRoute('GET', '/payments/:id/pay', 'Payments#pay');
        $this->addRoute('GET', '/merci', 'Payments#succeeded');
        $this->addRoute('GET', '/annulation', 'Payments#canceled');

        $this->addRoute('GET', '/invoices/:id/pdf', 'Invoices#downloadPdf', 'invoice download pdf');

        $this->addRoute('GET', '/api/account', 'api/Accounts#show');
        $this->addRoute('GET', '/api/account/login-url', 'api/Accounts#loginUrl');
        $this->addRoute('GET', '/api/account/expired-at', 'api/Accounts#expiredAt');
        $this->addRoute('POST', '/api/accounts/sync', 'api/Accounts#sync');

        $this->addRoute('GET', '/admin', 'admin/Payments#index', 'admin');
        $this->addRoute('GET', '/admin/login', 'admin/Auth#login', 'login');
        $this->addRoute('POST', '/admin/login', 'admin/Auth#createSession', 'create_session');
        $this->addRoute('POST', '/admin/logout', 'admin/Auth#deleteSession', 'logout');
        $this->addRoute('GET', '/admin/credits/new', 'admin/Credits#init', 'new admin credit');
        $this->addRoute('POST', '/admin/credits/new', 'admin/Credits#create', 'create admin credit');
        $this->addRoute('GET', '/admin/payments/new', 'admin/Payments#init', 'new admin payment');
        $this->addRoute('POST', '/admin/payments/new', 'admin/Payments#create', 'create admin payment');
        $this->addRoute('GET', '/admin/payments/:id', 'admin/Payments#show', 'admin payment');
        $this->addRoute('POST', '/admin/payments/:id/confirm', 'admin/Payments#confirm', 'confirm admin payment');
        $this->addRoute('POST', '/admin/payments/:id/destroy', 'admin/Payments#destroy', 'destroy admin payment');
        $this->addRoute('GET', '/admin/accounts', 'admin/Accounts#index', 'admin accounts');
        $this->addRoute('GET', '/admin/accounts/:id', 'admin/Accounts#show', 'admin account');
        $this->addRoute('POST', '/admin/accounts/:id', 'admin/Accounts#update', 'update admin account');

        $this->addRoute('POST', '/stripe/hooks', 'Stripe#hooks');

        $this->addRoute('CLI', '/migrations', 'cli/Migrations#index');
        $this->addRoute('CLI', '/migrations/setup', 'cli/Migrations#setup');
        $this->addRoute('CLI', '/migrations/rollback', 'cli/Migrations#rollback');
        $this->addRoute('CLI', '/migrations/create', 'cli/Migrations#create');
        $this->addRoute('CLI', '/payments/complete', 'cli/Payments#complete');
        $this->addRoute('CLI', '/accounts', 'cli/Accounts#index');
        $this->addRoute('CLI', '/accounts/create', 'cli/Accounts#create');
        $this->addRoute('CLI', '/accounts/login-url', 'cli/Accounts#loginUrl');
        $this->addRoute('CLI', '/accounts/remind', 'cli/Accounts#remind');
        $this->addRoute('CLI', '/accounts/clear', 'cli/Accounts#clear');
    }
}
