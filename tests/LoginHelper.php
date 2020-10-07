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
     *
     * @return array
     */
    public function loginAdmin()
    {
        \Website\utils\CurrentUser::logAdminIn();
        return \Website\utils\CurrentUser::get();
    }

    /**
     * Simulate a user who logs in.
     *
     * @param array $account_values
     *
     * @return array
     */
    public function loginUser($account_values = [])
    {
        $account_factory = new \Minz\Tests\DatabaseFactory('account');
        $account_id = $account_factory->create($account_values);
        \Website\utils\CurrentUser::logUserIn($account_id);
        return \Website\utils\CurrentUser::get();
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
