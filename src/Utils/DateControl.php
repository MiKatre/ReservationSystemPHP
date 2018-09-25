<?php

namespace App\Utils;

class DateControl
{
    // Check if the date in the future, when the museum is open.
    public function isDateValid(\DateTime $date){
        date_default_timezone_set('Europe/Paris');
        $dayOfWeek = (int) $date->format('w');  // 0 for sunday -> 6 for saturday
        $dayOfMonth = (int) $date->format('j'); // 1 to 31
        $month = (int) $date->format('n'); // 1 to 12
        $year =(int) $date->format('Y'); // year
        $today = new \DateTime($date->format('Ymd'));
        $now = new \DateTime();
        $today->setTime( 0, 0, 0 );
        $now->setTime( 0, 0, 0 );

        // If past day
        if($today < $now)
            return false;
        // If Saturday or Sunday
        if($dayOfWeek === 2 || $dayOfWeek === 0)
            return false;
        // 25 décembre
        if(($dayOfMonth === 25) && ($month === 12))
            return false;
        //1er novembre ou 1er mai
        if(($dayOfMonth === 1) && ($dayOfMonth === 11 || $dayOfMonth === 12))
            return false;

        return true;
    }

    public function allowFullDay(\DateTime $selectedDay) {
        date_default_timezone_set('Europe/Paris');

        $today = new \DateTime();
        $today->setTime( 0, 0, 0 );

        $selectedDay =new \DateTime($selectedDay->format('Ymd'));
        $selectedDay->setTime( 0, 0, 0 );

        $isToday = $today->getTimestamp() === $selectedDay->getTimestamp();

        if (!$isToday) return true;
        
        return (date('H') < 14);
    }
}