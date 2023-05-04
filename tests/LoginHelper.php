<?php

namespace tests;

use Website\utils\CurrentUser;

/**
 * Provide login utility methods during tests.
 *
 * @phpstan-import-type User from CurrentUser
 * @phpstan-import-type ModelValues from \Minz\Database\Recordable
 *
 * @author  Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
trait LoginHelper
{
    /**
     * Simulate an admin who logs in.
     *
     * @return User
     */
    public function loginAdmin(): array
    {
        CurrentUser::logAdminIn();

        $user = CurrentUser::get();

        assert($user !== null);

        return $user;
    }

    /**
     * Simulate a user who logs in.
     *
     * @param ModelValues $account_values
     *
     * @return User
     */
    public function loginUser(array $account_values = []): array
    {
        $account = factories\AccountFactory::create($account_values);
        CurrentUser::logUserIn($account->id);

        $user = CurrentUser::get();

        assert($user !== null);

        return $user;
    }

    /**
     * Simulate a user who logs out. It is called before each test to make sure
     * to reset the context.
     *
     * @after
     */
    public function logout(): void
    {
        CurrentUser::logOut();
    }
}
