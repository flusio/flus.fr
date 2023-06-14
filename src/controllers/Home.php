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
        return Response::ok('home/project.phtml');
    }

    public function pricing(Request $request): Response
    {
        $current_year = intval(\Minz\Time::now()->format('Y'));
        $total_revenue = models\Payment::findTotalRevenue($current_year) / 100;
        $revenue_target = 5000;
        $percent_target = min(100, max(5, $total_revenue * 100 / $revenue_target));
        $common_pot_revenue = models\Payment::findCommonPotRevenue($current_year) / 100;
        $subscriptions_revenue = models\Payment::findSubscriptionsRevenue($current_year) / 100;

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
            'common_pot_revenue' => number_format($common_pot_revenue, 2, ',', '&nbsp;'),
            'subscriptions_revenue' => number_format($subscriptions_revenue, 2, ',', '&nbsp;'),
        ]);
        $response->setContentSecurityPolicy('style-src', "'self' 'unsafe-inline'");
        return $response;
    }

    public function tour(Request $request): Response
    {
        $page = $request->param('page');
        if (!$page) {
            return Response::redirect('tour page', ['page' => 'journal']);
        }

        $pages_to_views = [
            'journal' => 'news',
            'flux' => 'feeds',
            'signets' => 'bookmarks',
            'collections' => 'collections',
            'pocket' => 'pocket',
            'opml' => 'opml',
        ];
        if (!isset($pages_to_views[$page])) {
            return Response::notFound('not_found.phtml');
        }

        $view = $pages_to_views[$page];
        $response = Response::ok("home/tour/{$view}.phtml");
        $response->setContentSecurityPolicy('media-src', "'self' flus.fr");
        return $response;
    }

    public function funding(Request $request): Response
    {
        return Response::redirect('pricing');
    }

    public function credits(Request $request): Response
    {
        return Response::ok('home/credits.phtml');
    }

    public function legal(Request $request): Response
    {
        return Response::ok('home/legal.phtml');
    }

    public function cgv(Request $request): Response
    {
        return Response::ok('home/cgv.phtml');
    }

    public function robots(Request $request): Response
    {
        return Response::ok('home/robots.txt');
    }

    public function sitemap(Request $request): Response
    {
        return Response::ok('home/sitemap.xml');
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
            $mailer->sendMessage($message);
        }

        return Response::ok('home/contact.phtml', [
            'email_sent' => true,
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
