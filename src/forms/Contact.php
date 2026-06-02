<?php

namespace Website\forms;

use Minz\Form;
use Minz\Validable;
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

    #[Validable\Check]
    public function checkSpamContent(): void
    {
        $splitted_content = explode(' ', $this->content);
        $count_words = count($splitted_content);
        if ($count_words < 3) {
            $this->addError('content', 'invalid', 'Veuillez être plus explicite dans votre demande.');
            return;
        }
    }
}
