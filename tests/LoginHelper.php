<?php

namespace tests;

/**
 * Provide login utility methods during tests.
 *
 * @author  Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
trait LoginHelper
{
    /**
     * Simulate an admin who logs in.
     */
    public function loginAdmin()
    {
        \Website\utils\CurrentUser::logAdminIn();
    }

    /**
     * Simulate a user who logs out. It is called before each test to make sure
     * to reset the context.
     *
     * @before
     */
    public function logout()
    {
        \Website\utils\CurrentUser::logOut();
    }
}
