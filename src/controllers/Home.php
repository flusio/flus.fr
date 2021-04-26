<?php

namespace Website\controllers;

use Website\mailers;
use Website\models;

class Home
{
    public function index()
    {
        return \Minz\Response::ok('home/index.phtml');
    }

    public function project()
    {
        return \Minz\Response::ok('home/project.phtml');
    }

    public function pricing()
    {
        $payment_dao = new models\dao\Payment();
        $current_year = intval(\Minz\Time::now()->format('Y'));
        $total_revenue = $payment_dao->findTotalRevenue($current_year) / 100;
        $revenue_target = 10000;
        $percent_target = min(100, max(5, $total_revenue * 100 / $revenue_target));
        $common_pot_revenue = $payment_dao->findCommonPotRevenue($current_year) / 100;
        $subscriptions_revenue = $payment_dao->findSubscriptionsRevenue($current_year) / 100;

        $response = \Minz\Response::ok('home/pricing.phtml', [
            'total_revenue' => number_format($total_revenue, 2, ',', '&nbsp;'),
            'revenue_target' => number_format($revenue_target, 0, ',', '&nbsp;'),
            'percent_target' => intval($percent_target),
            'common_pot_revenue' => number_format($common_pot_revenue, 2, ',', '&nbsp;'),
            'subscriptions_revenue' => number_format($subscriptions_revenue, 2, ',', '&nbsp;'),
        ]);
        $response->setContentSecurityPolicy('style-src', "'self' 'unsafe-inline'");
        return $response;
    }

    public function funding()
    {
        return \Minz\Response::redirect('pricing');
    }

    public function credits()
    {
        return \Minz\Response::ok('home/credits.phtml');
    }

    public function legal()
    {
        return \Minz\Response::ok('home/legal.phtml');
    }

    public function cgv()
    {
        return \Minz\Response::ok('home/cgv.phtml');
    }

    public function robots()
    {
        return \Minz\Response::ok('home/robots.txt');
    }

    public function sitemap()
    {
        return \Minz\Response::ok('home/sitemap.xml');
    }

    public function contact($request)
    {
        return \Minz\Response::ok('home/contact.phtml', [
            'email' => '',
            'subject' => $request->param('subject', ''),
            'content' => '',
        ]);
    }

    public function sendContactEmail($request)
    {
        $email = $request->param('email');
        $subject = $request->param('subject');
        $content = $request->param('content');

        $message = models\Message::init($email, $subject, $content);
        $errors = $message->validate();
        if ($errors) {
            return \Minz\Response::badRequest('home/contact.phtml', [
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

            $mailer = new mailers\Support();
            $mailer->sendNotification($message);
        }

        return \Minz\Response::ok('home/contact.phtml', [
            'email_sent' => true,
        ]);
    }

    public function security()
    {
        return \Minz\Response::ok('home/security.phtml');
    }

    public function securityTxt()
    {
        return \Minz\Response::ok('home/security.txt');
    }
}
