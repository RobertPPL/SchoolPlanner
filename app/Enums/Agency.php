<?php

namespace App\Enums;

class Agency
{
    /**
     * @var string Role name for application administrator.
     */
    public const SUPERVISOR = 'Supervisor';

    //oddziały
    public const POZNAN = 'Poznan';
    public const KATOWICE = 'Katowice';
    public const BIALYSTOK = 'Białystok';
    public const KRAKOW = 'Kraków';
    public const WROCLAW = 'Wrocław';
    public const LUBLIN = 'Lublin';

    public static function rolesList()
    {
        return [
            self::SUPERVISOR,
            self::BIALYSTOK,
            self::KATOWICE,
            self::KRAKOW,
            self::POZNAN,
            self::WROCLAW,
            self::LUBLIN
        ];
    }
}