<?php

namespace App\tests\Utils;

use App\Utils\PriceControl;
use PHPUnit\Framework\TestCase;

class PriceControlTest extends TestCase
{
  public function testGetPriceHTForFullDayAdultNoDiscount(){
    $priceControl = new PriceControl();
    $time = strtotime("-30 year", time());
    $dateOfBirth = new \DateTime(date("Y-m-d", $time));
    // discount = false, fullDay = true
    $result = $priceControl->getPriceHT($dateOfBirth, false, true);

    $this->assertEquals(1600, $result->price);
  }

  public function testGetPriceHTForFullDayAdultWithDiscount(){
    $priceControl = new PriceControl();
    $time = strtotime("-30 year", time());
    $dateOfBirth = new \DateTime(date("Y-m-d", $time));
    // discount = false, fullDay = true
    $result = $priceControl->getPriceHT($dateOfBirth, true, true);

    $this->assertEquals(1440, $result->price);
  }

  public function testGetPriceTTCAdultFullDayNoDiscount(){
    $priceControl = new PriceControl();
    $time = strtotime("-30 year", time());
    $dateOfBirth = new \DateTime(date("Y-m-d", $time));
    // discount = false, fullDay = true
    $result = $priceControl->getPriceTTC($dateOfBirth, false, true);

    $this->assertEquals(1920, $result->price);
  }

  public function testGetPriceTTCChildrenHalfDayDiscount(){
    $priceControl = new PriceControl();
    $time = strtotime("-4 year", time());
    $dateOfBirth = new \DateTime(date("Y-m-d", $time));
    // discount = false, fullDay = true
    $result = $priceControl->getPriceTTC($dateOfBirth, true, false);

    $this->assertEquals(432, $result->price);
  }

}