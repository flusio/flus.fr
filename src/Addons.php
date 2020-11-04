<?php

namespace Website;

use Minz\Response;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Addons
{
    /**
     * @response 200
     */
    public function geckoUpdate($request)
    {
        return \Minz\Response::ok('addons/gecko_update.json');
    }

    /**
     * @response 404
     *     If there is no extension files
     * @response 302
     *     On success
     */
    public function geckoLatest($request)
    {
        $addons_path = \Minz\Configuration::$app_path . '/public/addons';
        $files = glob($addons_path . '/*.xpi');
        if (!$files) {
            return Response::notFound('not_found.phtml');
        }

        rsort($files, SORT_NATURAL);
        $latest_filename = basename($files[0]);
        return Response::found(\Minz\Url::path() . '/addons/' . $latest_filename);
    }
}
