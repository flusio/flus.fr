<?php

namespace Website\controllers\admin\accounts;

use Minz\Request;
use Minz\Response;
use Website\auth;
use Website\controllers\admin\BaseController;
use Website\forms;
use Website\models;

class Payments extends BaseController
{
    /**
     * @request_param string id
     *
     * @response 200
     *     On success.
     *
     * @throws auth\NotAdminError
     *     If the user is not connected as an admin.
     * @throws \Minz\Errors\MissingRecordError
     *     If the account doesn't exist.
     */
    public function new(Request $request): Response
    {
        auth\CurrentUser::requireAdmin();

        $account = models\Account::requireFromRequest($request);
        $payment = models\Payment::initSubscriptionFromAccount($account);
        $form = new forms\admin\NewAccountPayment(model: $payment);

        return Response::ok('admin/accounts/payments/new.phtml', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    /**
     * @request_param string id
     * @request_param int euros_amount
     * @request_param int quantity
     * @request_param string additional_references
     * @request_param bool generate_invoice
     * @request_param string csrf_token
     *
     * @response 400
     *     If at least one parameter is invalid.
     * @response 200
     *     On success.
     *
     * @throws auth\NotAdminError
     *     If the user is not connected as an admin.
     * @throws \Minz\Errors\MissingRecordError
     *     If the account doesn't exist.
     */
    public function create(Request $request): Response
    {
        auth\CurrentUser::requireAdmin();

        $account = models\Account::requireFromRequest($request);
        $payment = models\Payment::initSubscriptionFromAccount($account);
        $form = new forms\admin\NewAccountPayment(model: $payment);
        $form->handleRequest($request);

        if (!$form->validate()) {
            return Response::badRequest('admin/accounts/payments/new.phtml', [
                'account' => $account,
                'form' => $form,
            ]);
        }

        $payment = $form->model();
        if ($form->generate_invoice) {
            $payment->invoice_number = models\Payment::generateInvoiceNumber();
        }
        $payment->save();

        return Response::redirect('admin account', [
            'id' => $account->id,
        ]);
    }
}
