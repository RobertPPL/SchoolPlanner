<?php

namespace App\Services;

use DateTime;

class DateLinkGenerator
{
    private DateTime $date;
    private string $route;

    public function __construct(string $date, string $route)
    {
        $this->date = new DateTime($date);
        $this->route = $route;
    }

    public function nextDay($format = 'Y-m-d'): string
    {
        $date = clone $this->date;
        return route($this->route, $date->modify('+1 day')->format($format));
    }

    public function previousDay($format = 'Y-m-d'): string
    {
        $date = clone $this->date;
        return route($this->route, $date->modify('-1 day')->format($format));
    }

    public function todayDay(): string
    {
        return date("Y-m-d");
    }
}