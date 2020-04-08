<?php

namespace Website\models\dao;

use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Payment extends \Minz\DatabaseModel
{
    public function __construct()
    {
        $properties = array_keys(models\Payment::PROPERTIES);
        parent::__construct('payments', 'id', $properties);
    }

    /**
     * Create a payment if it doesn't exist, or update an existing one
     *
     * @param \Website\models\Payment $model
     *
     * @return integer|boolean Return the id on creation, or true on update.
     */
    public function save($model)
    {
        if ($model->id === null) {
            $values = $model->toValues();
            $values['created_at'] = \Minz\Time::now()->getTimestamp();
            return $this->create($values);
        } else {
            $values = $model->toValues();
            $this->update($model->id, $values);
            return true;
        }
    }

    /**
     * Return a raw payment (order is not guaranteed)
     *
     * @return array|null
     */
    public function take()
    {
        $all = $this->listAll();
        if (!empty($all)) {
            return $all[0];
        } else {
            return null;
        }
    }
}