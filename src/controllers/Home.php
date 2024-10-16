<?php

namespace Website\controllers;

use Minz\Request;
use Minz\Response;
use Website\mailers;
use Website\models;
use Website\utils;

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
        $user = utils\CurrentUser::get();
        if ($user && !utils\CurrentUser::isAdmin()) {
            $account = models\Account::find($user['account_id']);
        }

        $response = Response::ok('home/pricing.phtml', [
            'account' => $account,
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
        $user = utils\CurrentUser::get();
        if ($user && !utils\CurrentUser::isAdmin()) {
            $account = models\Account::find($user['account_id']);
            $email = $account->email ?? '';
        }

        return Response::ok('home/contact.phtml', [
            'email' => $email,
            'subject' => $request->param('subject', ''),
            'content' => '',
        ]);
    }

    public function sendContactEmail(Request $request): Response
    {
        $email = $request->param('email', '');
        $subject = $request->param('subject', '');
        $content = $request->param('content', '');

        $message = new models\Message($email, $subject, $content);
        $errors = $message->validate();
        if ($errors) {
            return Response::badRequest('home/contact.phtml', [
                'email' => $email,
                'subject' => $subject,
                'content' => $content,
                'errors' => $errors,
            ]);
        }

        // The website input is just a trap for bots, don't fill it!
        $honeypot = $request->param('website');
        if (!$honeypot) {
            $mailer = new mailers\Support();
            $sent = $mailer->sendMessage($message);

            if (!$sent) {
                return Response::badRequest('home/contact.phtml', [
                    'email' => $email,
                    'subject' => $subject,
                    'content' => $content,
                    'errors' => [
                        '_' => 'Une erreur est survenue durant l’envoi de votre message. Veuillez réessayer plus tard.',
                    ],
                ]);
            }
        }

        return Response::ok('home/contact.phtml', [
            'email_sent' => true,
            'email' => $email,
            'subject' => $subject,
            'content' => $content,
        ]);
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
