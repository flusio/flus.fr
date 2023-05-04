<?php

namespace Website\utils;

/**
 * @phpstan-type User array{'account_id': string}
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class CurrentUser
{
    public static function logAdminIn(): void
    {
        $_SESSION['account_id'] = 'the administrator';
    }

    public static function logUserIn(string $account_id): void
    {
        if ($account_id === 'the administrator') {
            return; // should be useless, just additional security
        }

        $_SESSION['account_id'] = $account_id;
    }

    public static function logOut(): void
    {
        unset($_SESSION['account_id']);
    }

    /**
     * @return ?User
     */
    public static function get(): ?array
    {
        if (isset($_SESSION['account_id'])) {
            return [
                'account_id' => $_SESSION['account_id'],
            ];
        } else {
            return null;
        }
    }

    public static function isAdmin(): bool
    {
        $user = self::get();
        return $user && $user['account_id'] === 'the administrator';
    }
}
