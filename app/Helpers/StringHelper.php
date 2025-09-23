<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Get last character
     *
     * @param string $string
     * @return string
     */
    public static function getLastChar($string)
    {
        return substr($string, -1);
    }
}
