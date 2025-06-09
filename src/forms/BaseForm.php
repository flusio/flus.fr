<?php

namespace Website\forms;

use Minz\Form;

/**
 * @template T of object = \stdClass
 *
 * @phpstan-extends Form<T>
 *
 * @author  Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class BaseForm extends Form
{
    use Form\Csrf;

    public function csrfErrorMessage(): string
    {
        return 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.';
    }
}
