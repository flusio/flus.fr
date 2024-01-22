<?php

namespace Website\cli;

use Minz\Request;
use Minz\Response;
use Website\models;
use Website\mailers;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Payments
{
    /**
     * @request_param string filename
     */
    public function import(Request $request): mixed
    {
        $filename = $request->param('filename', '');
        $file_content = file_get_contents($filename);
        if (!$file_content) {
            return Response::text(400, "File {$filename} doesn't exist or is not readable.");
        }

        $data = json_decode($file_content, true);
        if ($data === false) {
            return Response::text(400, "File {$filename} is not valid JSON.");
        }

        $missing = [];
        $imported = [];

        foreach ($data as $id_to_account_id) {
            $id = $id_to_account_id['id'];
            $account_id = $id_to_account_id['account_id'];

            $payment = models\Payment::find($id);

            if (!$payment) {
                $missing[] = $id;
                continue;
            }

            $payment->account_id = $account_id;
            $payment->save();
            $imported[] = $id;
        }

        $missing = implode("\n", $missing);
        $imported = implode("\n", $imported);
        yield Response::text(200, "Missing:\n{$missing}\n");
        yield Response::text(200, "Imported:\n{$imported}\n");
    }
}
