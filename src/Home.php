<?php

namespace Website;

class Home
{
    public function index()
    {
        $payment_dao = new models\dao\Payment();
        $total_revenue = $payment_dao->findTotalRevenue() / 100;
        $revenue_target = 30000;
        $percent_target = min(100, max(5, $total_revenue * 100 / $revenue_target));

        $response = \Minz\Response::ok('home/index.phtml', [
            'total_revenue' => number_format($total_revenue, 2, ',', '&nbsp;'),
            'revenue_target' => number_format($revenue_target, 0, ',', '&nbsp;'),
            'percent_target' => intval($percent_target),
        ]);
        $response->setContentSecurityPolicy('style-src', "'self' 'unsafe-inline'");
        return $response;
    }

    public function funding()
    {
        $payment_dao = new models\dao\Payment();
        $total_revenue = $payment_dao->findTotalRevenue() / 100;
        $revenue_target = 30000;
        $percent_target = min(100, max(5, $total_revenue * 100 / $revenue_target));
        $common_pot_amount = $payment_dao->findCommonPotRevenue() / 100;
        $subscriptions_amount = $payment_dao->findSubscriptionsRevenue() / 100;

        $response = \Minz\Response::ok('home/funding.phtml', [
            'total_revenue' => number_format($total_revenue, 2, ',', '&nbsp;'),
            'revenue_target' => number_format($revenue_target, 0, ',', '&nbsp;'),
            'percent_target' => intval($percent_target),
            'common_pot_amount' => number_format($common_pot_amount, 2, ',', '&nbsp;'),
            'subscriptions_amount' => number_format($subscriptions_amount, 2, ',', '&nbsp;'),
        ]);
        $response->setContentSecurityPolicy('style-src', "'self' 'unsafe-inline'");
        return $response;
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
        }

        return \Minz\Response::ok('home/contact.phtml', [
            'email_sent' => true,
        ]);
    }
}
