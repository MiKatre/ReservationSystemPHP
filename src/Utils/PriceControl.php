<?php

namespace App\Utils;

use App\Entity\Ticket;

/**
 * Class PriceControl
 * @package App\Utils
 */
class PriceControl
{
    const TVA = 20 / 100;

    /**
     * @param $dateOfBirth
     * @return int
     */
    private function getAge($dateOfBirth) {
        $diff = $dateOfBirth->diff(new \DateTime());
        return (int) ($diff->format('%y'));
    }

    /**
     * @param $dateOfBirth
     * @param $discount
     * @param $isFullDay
     * @return object
     */
    public function getPriceHT($dateOfBirth, $discount, $isFullDay){
        $age = (int) $this->getAge($dateOfBirth);

        // à partir de 12 ans
        $normal = (object) ['price' => Ticket::NORMAL_PRICE_HT, 'name' => 'Normal'];
        // de 4 à 12 ans
        $children = (object) ['price' => Ticket::CHILDREN_PRICE_HT, 'name' => 'Enfant'];
        // à partir de 60 ans
        $senior = (object) ['price' => Ticket::SENIOR_PRICE_HT, 'name' => 'Senior'];
        // Moins de 4 ans
        $baby = (object) ['price' => Ticket::BABY_PRICE_HT, 'name' => 'Enfant'];

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

    /**
     * @param $dateOfBirth
     * @param $discount
     * @param $isFullDay
     * @return object
     */
    public function getPriceTTC($dateOfBirth, $discount, $isFullDay){
        $formula = $this->getPriceHT($dateOfBirth, $discount, $isFullDay);
        $formula->price =  $formula->price + ($formula->price * PriceControl::TVA);
        return $formula;
    }

    /**
     * @param $tickets
     * @return int
     */
    public function getTotalHT($tickets) {
        $amount = 0;
        foreach($tickets as $ticket) {
            $amount = $amount + $ticket->getPrice();
        }
        return $amount;
    }

    /**
     * @param $tickets
     * @return float|int
     */
    public function getTotalTTC($tickets) {
        $amount = $this->getTotalHT($tickets);
        return ($amount * PriceControl::TVA + $amount) ;
    }
}