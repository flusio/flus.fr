<?php

namespace Website\forms;

use AltchaOrg;
use Minz\Form;
use Minz\Validable;

trait Altcha
{
    #[Form\Field(bind: false)]
    public string $altcha = '';

    #[Validable\Check]
    public function checkAltcha(): void
    {
        if (!$this->altcha) {
            $this->addError('@base', 'altcha_missing', $this->altchaMissingErrorMessage());
            return;
        }

        $altcha = new AltchaOrg\Altcha\Altcha(\Minz\Configuration::$secret_key);
        $verified = $altcha->verifySolution($this->altcha);

        if (!$verified) {
            $this->addError('@base', 'altcha_invalid', $this->altchaInvalidErrorMessage());
            return;
        }
    }

    public function altchaMissingErrorMessage(): string
    {
        return 'Le captcha n’a pas été renseigné, veuillez cocher "Pas un robot".';
    }

    public function altchaInvalidErrorMessage(): string
    {
        return 'Le captcha est invalide, veuillez retenter de valider le formulaire.';
    }
}
