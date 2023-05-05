<?php

namespace Website\cli;

use Minz\Request;
use Minz\Response;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Help
{
    /**
     * @response 200
     */
    public function show(Request $request): Response
    {
        $usage = "Usage: php cli COMMAND [--OPTION=VALUE]...\n";
        $usage .= "\n";
        $usage .= "COMMAND can be one of the following:\n";
        $usage .= "  help                     Show this help\n";
        $usage .= "\n";
        $usage .= "  accounts                 List the accounts\n";
        $usage .= "  accounts create          Create a new account\n";
        $usage .= "      --email=TEXT         The email of the account\n";
        $usage .= "  accounts login-url       Generate a URL to login as a user\n";
        $usage .= "      --account_id=ID      The ID of the account\n";
        $usage .= "      [--service=TEXT]     The name of the service to redirect to (freshrss or flusio)\n";
        $usage .= "  accounts remind          Send the remind emails\n";
        $usage .= "  accounts clear           Clear the non-synced accounts\n";
        $usage .= "\n";
        $usage .= "  payments complete        Complete the paid payments\n";
        $usage .= "\n";
        $usage .= "  migrations               List the migrations\n";
        $usage .= "  migrations setup         Initialize or migrate the application\n";
        $usage .= "  migrations rollback      Rollback the latest migrations\n";
        $usage .= "      [--steps=INT]        The number of migrations to rollback\n";
        $usage .= "  migrations create        Create a new migration\n";
        $usage .= "      --name=TEXT          The name of the migration (only chars from A to Z and numbers)\n";

        return Response::text(200, $usage);
    }
}
