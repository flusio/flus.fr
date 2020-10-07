<?php

namespace Website\utils;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class CurrentUser
{
    public static function logAdminIn()
    {
        $_SESSION['account_id'] = 'the administrator';
    }

    /**
     * @param string $account_id
     */
    public static function logUserIn($account_id)
    {
        if ($account_id === 'the administrator') {
            return; // should be useless, just additional security
        }

        $_SESSION['account_id'] = $account_id;
    }

    public static function logOut()
    {
        unset($_SESSION['account_id']);
    }

    /**
     * @return array|null
     */
    public static function get()
    {
        if (isset($_SESSION['account_id'])) {
            return [
                'account_id' => $_SESSION['account_id'],
            ];
        } else {
            return null;
        }
    }

    /**
     * @return boolean
     */
    public static function isAdmin()
    {
        $user = self::get();
        return $user && $user['account_id'] === 'the administrator';
    }
}
