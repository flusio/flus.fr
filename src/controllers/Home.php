<?php

namespace Website\controllers;

use Minz\Controller;
use Minz\Request;
use Minz\Response;
use Website\auth;
use Website\forms;
use Website\mailers;
use Website\models;
use Website\services;

class Home
{
    public function index(Request $request): Response
    {
        return Response::ok('home/index.phtml');
    }

    public function project(Request $request): Response
    {
        return Response::redirect('home');
    }

    public function features(Request $request): Response
    {
        return Response::ok('home/features.phtml');
    }

    public function pricing(Request $request): Response
    {
        $current_year = intval(\Minz\Time::now()->format('Y'));
        $total_revenue = models\Payment::findTotalRevenue($current_year) / 100;
        $revenue_target = \Minz\Configuration::$application['financial_goal'];
        $percent_target = min(100, $total_revenue * 100 / $revenue_target);

        $account = null;
        $user = auth\CurrentUser::get();
        if ($user && !auth\CurrentUser::isAdmin()) {
            $account = models\Account::find($user['account_id']);
        }

        $response = Response::ok('home/pricing.phtml', [
            'account' => $account,
            'count_active_accounts' => models\Account::countActive(),
            'contribution_price' => models\Payment::contributionPrice(),
            'total_revenue' => number_format($total_revenue, 2, ',', '&nbsp;'),
            'revenue_target' => number_format($revenue_target, 0, ',', '&nbsp;'),
            'percent_target' => intval($percent_target),
        ]);
        $response->setContentSecurityPolicy('style-src', "'self' 'unsafe-inline'");
        return $response;
    }

    public function tour(Request $request): Response
    {
        return Response::redirect('features');
    }

    public function funding(Request $request): Response
    {
        return Response::redirect('pricing');
    }

    public function credits(Request $request): Response
    {
        return Response::ok('home/credits.phtml');
    }

    public function robots(Request $request): Response
    {
        return Response::ok('home/robots.txt');
    }

    public function sitemap(Request $request): Response
    {
        if ($request->path() === '/sitemap.xml') {
            return Response::ok('home/sitemap.xml');
        } else {
            return Response::ok('home/sitemap.phtml');
        }
    }

    public function contact(Request $request): Response
    {
        $email = '';
        $user = auth\CurrentUser::get();
        if ($user && !auth\CurrentUser::isAdmin()) {
            $account = models\Account::find($user['account_id']);
            $email = $account->email ?? '';
        }

        $form = new forms\Contact([
            'email' => $email,
            'subject' => $request->parameters->getString('subject', ''),
        ]);

        return Response::ok('home/contact.phtml', [
            'form' => $form,
        ]);
    }

    public function sendContactMessage(Request $request): Response
    {
        $message = new models\Message();
        $form = new forms\Contact(model: $message);

        $form->handleRequest($request);

        if (!$form->validate()) {
            return Response::badRequest('home/contact.phtml', [
                'form' => $form,
            ]);
        }

        $message = $form->model();

        $bileto = new services\Bileto();

        if ($bileto->isEnabled()) {
            $result = $bileto->sendMessage($message);
        } else {
            $mailer = new mailers\Support();
            try {
                $result = $mailer->sendMessage($message);
            } catch (\Minz\Errors\MailerError $e) {
                \Minz\Log::error($e->getMessage());
                $result = false;
            }
        }

        if (!$result) {
            $form->addError(
                '@base',
                'internal_error',
                'Une erreur est survenue durant l’envoi de votre message. Veuillez réessayer plus tard.'
            );

            return Response::badRequest('home/contact.phtml', [
                'form' => $form,
            ]);
        }

        return Response::ok('home/contact.phtml', [
            'message_sent' => true,
            'form' => $form,
        ]);
    }

    #[Controller\AfterAction(only: ['contact', 'sendContactMessage'])]
    public function setContactCSPHeaders(Request $request, Response $response): void
    {
        $response->setContentSecurityPolicy('worker-src', "'self' blob:");
        $response->setContentSecurityPolicy(
            'style-src-elem',
            "'self' 'sha256-pg+oQARqMq4wCazyrsMt8HY89BJkXkEFkwNWxg2iPdg=' 'unsafe-hashes'"
        );
    }

    public function security(Request $request): Response
    {
        return Response::ok('home/security.phtml');
    }

    public function securityTxt(Request $request): Response
    {
        return Response::ok('home/security.txt');
    }
}
