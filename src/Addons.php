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

    /**
     * @response 200
     */
    public function chromeUpdate($request)
    {
        return \Minz\Response::ok('addons/chrome_update.xml');
    }
}
