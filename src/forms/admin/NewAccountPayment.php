<?php

namespace Website\forms\admin;

use Minz\Form;
use Website\forms\BaseForm;
use Website\models;

/**
 * @phpstan-extends BaseForm<models\Payment>
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class NewAccountPayment extends BaseForm
{
    #[Form\Field(bind: 'setEurosAmount')]
    public int $euros_amount = 30;

    #[Form\Field]
    public int $quantity = 1;

    #[Form\Field]
    public string $additional_references = '';

    #[Form\Field(bind: false)]
    public bool $generate_invoice = true;
}
