<?php

namespace Website\controllers\admin;

use Minz\Request;
use Minz\Response;
use Website\auth;
use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class ProRegistrations extends BaseController
{
    /**
     * List the registrations to the pro offer
     *
     * @request_param string format
     *     `html` (default) or `csv`
     * @request_param string status
     *
     * @response 302 /admin/login
     *     If user is not connected as an admin
     * @response 200
     *     On success
     */
    public function index(Request $request): Response
    {
        auth\CurrentUser::requireAdmin();

        $pro_registrations = models\ProRegistration::listAll('created_at DESC');

        $format = $request->parameters->getString('format', 'html');
        if ($format === 'csv') {
            return Response::ok('admin/pro_registrations/index.txt', [
                'pro_registrations' => $pro_registrations,
            ]);
        } else {
            return Response::ok('admin/pro_registrations/index.phtml', [
                'pro_registrations' => $pro_registrations,
                'status' => $request->parameters->getString('status'),
            ]);
        }
    }

    /**
     * Delete a registration to the pro offer
     *
     * @request_param string id
     * @request_param string csrf
     *
     * @response 302 /admin/login
     *     If user is not connected as an admin
     * @response 404
     *     If the pro registration doesn't exist
     * @response 400
     *     If the CSRF token is invalid
     * @response 302 /admin/pro-registrations?status=pro_registration_deleted
     *     On success
     */
    public function delete(Request $request): Response
    {
        auth\CurrentUser::requireAdmin();

        $pro_registration = models\ProRegistration::requireFromRequest($request);

        if (!\Website\Csrf::validate($request->parameters->getString('csrf', ''))) {
            return Response::badRequest('admin/pro_registrations/index.phtml', [
                'pro_registrations' => models\ProRegistration::listAll('created_at DESC'),
                'status' => null,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $pro_registration->remove();

        return Response::redirect('admin pro registrations', [
            'status' => 'pro_registration_deleted',
        ]);
    }
}
