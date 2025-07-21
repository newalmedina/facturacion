<?php

namespace App\Services;

class UtilsService
{
    /**
     * Genera un color HEX a partir del hash del nombre
     */
    public static function generateColorByName(string $name): string
    {
        $hash = md5($name);
        return '#' . substr($hash, 0, 6);
    }
}
