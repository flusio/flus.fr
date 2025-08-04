<?php

namespace Website\forms;

use Minz\Form;
use Website\models;

/**
 * @phpstan-extends BaseForm<models\Message>
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Contact extends BaseForm
{
    use Altcha;

    #[Form\Field(transform: '\Minz\Email::sanitize')]
    public string $email = '';

    #[Form\Field(transform: 'trim')]
    public string $subject = '';

    #[Form\Field(transform: 'trim')]
    public string $content = '';
}
