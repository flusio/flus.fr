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
        $usage .= "  accounts login           Generate a URL to login as a user\n";
        $usage .= "      --id=ID              The ID of the account\n";
        $usage .= "\n";
        $usage .= "  migrations               List the migrations\n";
        $usage .= "  migrations setup         Initialize or migrate the application\n";
        $usage .= "      [--seed=BOOL]        Whether you want to seed the application or not (default: false)\n";
        $usage .= "  migrations rollback      Rollback the latest migrations\n";
        $usage .= "      [--steps=INT]        The number of migrations to rollback\n";
        $usage .= "  migrations create        Create a new migration\n";
        $usage .= "      --name=TEXT          The name of the migration (only chars from A to Z and numbers)\n";
        $usage .= "\n";
        $usage .= "  jobs                     List the registered jobs\n";
        $usage .= "  jobs watch               Wait for and execute jobs\n";
        $usage .= "      [--queue=TEXT]       The name of the queue to wait (default: all)\n";
        $usage .= "      [--stop-after=INT]   The max number of jobs to execute (default is infinite)\n";
        $usage .= "      [--sleep-duration=INT] The number of seconds between two cycles (default: 3)\n";
        $usage .= "  jobs show                Display info about a job\n";
        $usage .= "      --id=ID              The ID of the job\n";
        $usage .= "  jobs run                 Execute a single job\n";
        $usage .= "      --id=ID              The ID of the job\n";
        $usage .= "  jobs unfail              Discard the error of a job\n";
        $usage .= "      --id=ID              The ID of the job\n";
        $usage .= "  jobs unlock              Unlock a job\n";
        $usage .= "      --id=ID              The ID of the job\n";

        return Response::text(200, $usage);
    }
}
