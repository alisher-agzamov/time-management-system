<?php

namespace App\Helpers;

class Helper
{
    /**
     * Represent int minutes in str hours and minutes
     * @param $minues
     * @return string
     */
    public static function renderDuration($minutes)
    {
        $minutes = (int) $minutes;
        $hours  = intval($minutes / 60);
        $minutes = $minutes - $hours * 60;

        $duration = [];

        if($hours) {
            $duration[] = $hours . __('helpers.h');
        }

        if($minutes) {
            $duration[] = $minutes . __('helpers.m');
        }

        return implode(' ', $duration);
    }
}
