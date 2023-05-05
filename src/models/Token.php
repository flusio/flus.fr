<?php

namespace Website\models;

use Minz\Database;

/**
 * @author  Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
#[Database\Table(name: 'tokens', primary_key: 'token')]
class Token
{
    use Database\Recordable;

    #[Database\Column]
    public string $token;

    #[Database\Column]
    public \DateTimeImmutable $created_at;

    #[Database\Column]
    public \DateTimeImmutable $expired_at;

    #[Database\Column]
    public ?\DateTimeImmutable $invalidated_at = null;

    /**
     * Initialize a token valid for a certain amount of time.
     *
     * @see \Minz\Time
     *
     * @param integer $number
     * @param string $duration
     * @param integer $complexity default is 32
     */
    public function __construct($number, $duration, $complexity = 32)
    {
        $this->token = \Minz\Random::hex($complexity);
        $this->expired_at = \Minz\Time::fromNow($number, $duration);
    }

    /**
     * Return whether the token has expired.
     *
     * @return boolean
     */
    public function hasExpired()
    {
        return \Minz\Time::now() >= $this->expired_at;
    }

    /**
     * Return whether the token has been invalidated.
     *
     * @return boolean
     */
    public function isInvalidated()
    {
        return $this->invalidated_at !== null;
    }

    /**
     * Return whether the token is valid (i.e. not expired and not invalidated)
     *
     * @return boolean
     */
    public function isValid()
    {
        return !$this->hasExpired() && !$this->isInvalidated();
    }

    /**
     * Return wheter the token is going to expire in the next $number of $units.
     *
     * @see https://www.php.net/manual/datetime.formats.relative.php
     *
     * @param integer $number
     * @param string $unit
     *
     * @return boolean
     */
    public function expiresIn($number, $unit)
    {
        return \Minz\Time::fromNow($number, $unit) >= $this->expired_at;
    }
}
