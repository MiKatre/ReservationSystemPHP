<?php

namespace App\Utils;

use App\Entity\Ticket;

class PriceControl
{
    const TVA = 20 / 100;

    private function getAge($dateOfBirth) {
        $diff = $dateOfBirth->diff(new \DateTime());
        return (int) ($diff->format('%y'));
    }

    public function getPriceHT($dateOfBirth, $discount, $isFullDay){
        $age = (int) $this->getAge($dateOfBirth);

        // à partir de 12 ans
        $normal = (object) ['price' => Ticket::NORMAL_PRICE, 'name' => 'Normal'];
        // de 4 à 12 ans
        $children = (object) ['price' => Ticket::CHILDREN_PRICE, 'name' => 'Enfant'];
        // à partir de 60 ans
        $senior = (object) ['price' => Ticket::SENIOR_PRICE, 'name' => 'Senior'];
        // Moins de 4 ans
        $baby = (object) ['price' => Ticket::BABY_PRICE, 'name' => 'Enfant'];

        if ($age >= 4 && $age <= 12)
            $formula = $children;
        else if($age >= 60)
            $formula = $senior;
        else if($age < 4 )
            $formula = $baby;
        else
            $formula = $normal;

        if ($formula->price > 0) {
            $formula->price = $isFullDay ? $formula->price : $formula->price * Ticket::HALF_DAY_DISCOUNT_PERCENT;
            $formula->price = $discount ? $formula->price - ($formula->price * Ticket::DISCOUNT_PERCENT) : $formula->price;
        }

        return $formula;
    }

    public function getPriceTTC($dateOfBirth, $discount, $isFullDay){
        $formula = $this->getPriceHT($dateOfBirth, $discount, $isFullDay);
        $formula->price =  $formula->price + ($formula->price * PriceControl::TVA);
        return $formula;
    }

    public function getTotalHT($tickets) {
        $amount = 0;
        foreach($tickets as $ticket) {
            $amount = $amount + $ticket->getPrice();
        }
        return $amount;
    }

    public function getTotalTTC($tickets) {
        $amount = $this->getTotalHT($tickets);
        return ($amount * PriceControl::TVA + $amount) ;
    }
}