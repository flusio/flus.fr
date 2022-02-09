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

        $this->addRoute('get', '/', 'Home#index', 'home');
        $this->addRoute('get', '/projet', 'Home#project', 'project');
        $this->addRoute('get', '/tarifs', 'Home#pricing', 'pricing');
        $this->addRoute('get', '/visite', 'Home#tour', 'tour');
        $this->addRoute('get', '/visite/:page', 'Home#tour', 'tour page');
        $this->addRoute('get', '/financement', 'Home#funding', 'funding');
        $this->addRoute('get', '/credits', 'Home#credits', 'credits');
        $this->addRoute('get', '/mentions-legales', 'Home#legal', 'legal');
        $this->addRoute('get', '/cgv', 'Home#cgv', 'cgv');
        $this->addRoute('get', '/contact', 'Home#contact', 'contact');
        $this->addRoute('post', '/contact', 'Home#sendContactEmail', 'send contact email');
        $this->addRoute('get', '/securite', 'Home#security', 'security');
        $this->addRoute('get', '/cagnotte', 'CommonPots#show', 'common pot');

        $this->addRoute('get', '/robots.txt', 'Home#robots', 'robots.txt');
        $this->addRoute('get', '/sitemap.xml', 'Home#sitemap', 'sitemap.xml');
        $this->addRoute('get', '/.well-known/security.txt', 'Home#securityTxt', 'security.txt');

        $this->addRoute('get', '/addons/updates.json', 'Addons#geckoUpdate');
        $this->addRoute('get', '/addons/gecko/latest', 'Addons#geckoLatest');

        $this->addRoute('get', '/account', 'Accounts#show', 'account');
        $this->addRoute('get', '/account/login', 'Accounts#login', 'account login');
        $this->addRoute('post', '/account/logout', 'Accounts#logout', 'account logout');
        $this->addRoute('post', '/account/reminder', 'Accounts#setReminder', 'account set reminder');
        $this->addRoute('get', '/account/address', 'Accounts#address', 'account address');
        $this->addRoute('post', '/account/address', 'Accounts#updateAddress', 'account update address');
        $this->addRoute('get', '/account/renew', 'Subscriptions#init', 'subscription init');
        $this->addRoute('post', '/account/renew', 'Subscriptions#renew', 'subscription renew');
        $this->addRoute('get', '/account/common-pot', 'CommonPots#show', 'common pot account');
        $this->addRoute(
            'get',
            '/account/common-pot/contribute',
            'CommonPots#contribution',
            'common pot contribution'
        );
        $this->addRoute(
            'post',
            '/account/common-pot/contribute',
            'CommonPots#contribute',
            'contribute common pot'
        );
        $this->addRoute('get', '/account/common-pot/use', 'CommonPots#usage', 'common pot usage');
        $this->addRoute('post', '/account/common-pot/use', 'CommonPots#use', 'use common pot');

        $this->addRoute('get', '/payments/:id/pay', 'Payments#pay');
        $this->addRoute('get', '/merci', 'Payments#succeeded');
        $this->addRoute('get', '/annulation', 'Payments#canceled');

        $this->addRoute('get', '/invoices/:id/pdf', 'Invoices#downloadPdf', 'invoice download pdf');

        $this->addRoute('get', '/api/account', 'api/Accounts#show');
        $this->addRoute('get', '/api/account/login-url', 'api/Accounts#loginUrl');
        $this->addRoute('get', '/api/account/expired-at', 'api/Accounts#expiredAt');
        $this->addRoute('post', '/api/accounts/sync', 'api/Accounts#sync');

        $this->addRoute('get', '/admin', 'admin/Payments#index', 'admin');
        $this->addRoute('get', '/admin/login', 'admin/Auth#login', 'login');
        $this->addRoute('post', '/admin/login', 'admin/Auth#createSession', 'create_session');
        $this->addRoute('post', '/admin/logout', 'admin/Auth#deleteSession', 'logout');
        $this->addRoute('get', '/admin/credits/new', 'admin/Credits#init', 'new admin credit');
        $this->addRoute('post', '/admin/credits/new', 'admin/Credits#create', 'create admin credit');
        $this->addRoute('get', '/admin/payments/new', 'admin/Payments#init', 'new admin payment');
        $this->addRoute('post', '/admin/payments/new', 'admin/Payments#create', 'create admin payment');
        $this->addRoute('get', '/admin/payments/:id', 'admin/Payments#show', 'admin payment');
        $this->addRoute('post', '/admin/payments/:id/confirm', 'admin/Payments#confirm', 'confirm admin payment');
        $this->addRoute('post', '/admin/payments/:id/destroy', 'admin/Payments#destroy', 'destroy admin payment');
        $this->addRoute('get', '/admin/accounts', 'admin/Accounts#index', 'admin accounts');
        $this->addRoute('get', '/admin/accounts/:id', 'admin/Accounts#show', 'admin account');
        $this->addRoute('post', '/admin/accounts/:id', 'admin/Accounts#update', 'update admin account');

        $this->addRoute('post', '/stripe/hooks', 'Stripe#hooks');

        $this->addRoute('cli', '/system/init', 'cli/System#init');
        $this->addRoute('cli', '/system/migrate', 'cli/System#migrate');
        $this->addRoute('cli', '/system/rollback', 'cli/System#rollback');
        $this->addRoute('cli', '/payments/complete', 'cli/Payments#complete');
        $this->addRoute('cli', '/accounts', 'cli/Accounts#index');
        $this->addRoute('cli', '/accounts/create', 'cli/Accounts#create');
        $this->addRoute('cli', '/accounts/login-url', 'cli/Accounts#loginUrl');
        $this->addRoute('cli', '/accounts/remind', 'cli/Accounts#remind');
    }
}
