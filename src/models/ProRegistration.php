<?php

namespace Website\models;

use Minz\Database;
use Minz\Validable;

/**
 * A registration to be notified of the upcoming pro offer.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
#[Database\Table(name: 'pro_registrations')]
class ProRegistration
{
    use Database\Recordable;
    use Database\Resource;
    use Validable;

    #[Database\Column]
    public string $id;

    #[Database\Column]
    public \DateTimeImmutable $created_at;

    #[Validable\Presence(message: 'Saisissez une adresse courriel.')]
    #[Validable\Email(message: 'Saisissez une adresse courriel valide.')]
    #[Database\Column]
    public string $email = '';

    #[Database\Column]
    public string $organisation = '';

    public function __construct()
    {
        $this->id = \Minz\Random::hex(32);
    }
}
