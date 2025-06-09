<?php

namespace Website\forms;

use Minz\Form;
use Minz\Request;
use Minz\Validable;
use Website\models;
use Website\utils;

/**
 * @phpstan-extends BaseForm<models\Account>
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Profile extends BaseForm
{
    #[Form\Field(transform: '\Minz\Email::sanitize')]
    public string $email = '';

    #[Form\Field]
    public string $entity_type = 'natural';

    #[Form\Field(transform: 'trim')]
    public string $company_vat_number = '';

    #[Form\Field(bind: false)]
    public bool $show_address = false;

    #[Form\Field(transform: 'trim')]
    public string $address_first_name = '';

    #[Form\Field(transform: 'trim')]
    public string $address_last_name = '';

    #[Form\Field(transform: 'trim')]
    public string $address_legal_name = '';

    #[Form\Field(transform: 'trim')]
    public string $address_address1 = '';

    #[Form\Field(transform: 'trim')]
    public string $address_postcode = '';

    #[Form\Field(transform: 'trim')]
    public string $address_city = '';

    #[Form\Field]
    public string $address_country = '';

    /**
     * @param array<string, mixed> $default_values
     */
    public function __construct(array $default_values = [], ?models\Account $model = null)
    {
        if ($model && $model->address_address1) {
            $default_values['show_address'] = true;
        }

        parent::__construct($default_values, $model);
    }

    #[Form\OnHandleRequest]
    public function sanitizeAddress(Request $request): void
    {
        if ($this->entity_type === 'natural') {
            $this->set('company_vat_number', '');
            $this->set('address_legal_name', '');
        } else {
            $this->set('show_address', true);
            $this->set('address_first_name', '');
            $this->set('address_last_name', '');
        }

        if (!$this->show_address) {
            $this->set('address_address1', '');
            $this->set('address_postcode', '');
            $this->set('address_city', '');
        }
    }

    #[Validable\Check]
    public function checkAddress(): void
    {
        if ($this->entity_type === 'natural') {
            if (!$this->address_first_name) {
                $this->addError(
                    'address_first_name',
                    'missing_first_name',
                    'Votre prénom est obligatoire.'
                );
            }

            if (!$this->address_last_name) {
                $this->addError(
                    'address_last_name',
                    'missing_last_name',
                    'Votre nom est obligatoire.'
                );
            }
        } elseif (!$this->address_legal_name) {
            $this->addError(
                'address_legal_name',
                'missing_legal_name',
                'Votre raison sociale est obligatoire.'
            );
        }

        if ($this->show_address) {
            if (!$this->address_address1) {
                $this->addError(
                    'address_address1',
                    'invalid_address',
                    'Votre adresse est incomplète.'
                );
            }

            if (!$this->address_postcode) {
                $this->addError(
                    'address_postcode',
                    'invalid_address',
                    'Votre adresse est incomplète.'
                );
            }

            if (!$this->address_city) {
                $this->addError(
                    'address_city',
                    'invalid_address',
                    'Votre adresse est incomplète.'
                );
            }
        }
    }

    /**
     * @return utils\Countries::COUNTRIES
     */
    public function countries(): array
    {
        return utils\Countries::listSorted();
    }
}
