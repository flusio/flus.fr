<?php

namespace Website\utils;

/**
 * Allow easy manipulation of supported countries.
 *
 * @phpstan-type CountryCode key-of<Countries::COUNTRIES>
 * @phpstan-type CountryLabel value-of<Countries::COUNTRIES>
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Countries
{
    public const COUNTRIES = [
        'AT' => 'Autriche',
        'BE' => 'Belgique',
        'BG' => 'Bulgarie',
        'CH' => 'Suisse',
        'CY' => 'Chypre',
        'CZ' => 'République tchèque',
        'DE' => 'Allemagne',
        'DK' => 'Danemark',
        'EE' => 'Estonie',
        'ES' => 'Espagne',
        'FI' => 'Finlande',
        'FR' => 'France',
        'GR' => 'Grèce',
        'HR' => 'Croatie',
        'HU' => 'Hongrie',
        'IE' => 'Irlande',
        'IT' => 'Italie',
        'LT' => 'Lituanie',
        'LU' => 'Luxembourg',
        'LV' => 'Lettonie',
        'MT' => 'Malte',
        'NL' => 'Pays-Bas',
        'PL' => 'Pologne',
        'PT' => 'Portugal',
        'RO' => 'Roumanie',
        'SE' => 'Suède',
        'SI' => 'Slovénie',
        'SK' => 'Slovaquie',
    ];

    /**
     * @return self::COUNTRIES
     */
    public static function listSorted(): array
    {
        $countries = self::COUNTRIES;
        uasort($countries, function ($country_1, $country_2) {
            if ($country_1 === 'France') {
                return -1;
            }
            if ($country_2 === 'France') {
                return 1;
            }
            return \strnatcmp($country_1, $country_2);
        });
        return $countries;
    }

    /**
     * @return CountryCode[]
     */
    public static function codes(): array
    {
        return array_keys(self::COUNTRIES);
    }

    /**
     * @param CountryCode $country_code
     *
     * @return CountryLabel
     */
    public static function codeToLabel(string $country_code): string
    {
        return self::COUNTRIES[$country_code];
    }

    public static function isSupported(string $country_code): bool
    {
        return isset(self::COUNTRIES[$country_code]);
    }
}
