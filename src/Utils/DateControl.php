<?php

namespace App\Utils;

class DateControl
{
    public function isDateValid(\DateTime $date){
        date_default_timezone_set('Europe/Paris');
        $dayOfWeek = $date->format('w');  // 0 for sunday -> 6 for saturday
        $dayOfMonth = $date->format('j'); // 1 to 31
        $month = $date->format('n'); // 1 to 12
        $year = $date->format('Y'); // year
        $today = new \DateTime($year + $month + $dayOfMonth);
        $now = new \DateTime();

        // If past day
        if($today < $now)
            return false;
        // If Saturday or Sunday
        if($dayOfWeek === 2 || $dayOfWeek === 0)
            return false;
        // 25 décembre
        if($dayOfMonth === 25 && $month === 12)
            return false;
        //1er novembre ou 1er mai
        if(($dayOfMonth === 1) && ($dayOfMonth === 11 || $dayOfMonth === 12))
            return false;

        return true;
    }

    public function allowFullDay() {

    }
}