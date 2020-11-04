<?php

namespace Website;

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
}
