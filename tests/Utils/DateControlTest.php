<?php

namespace App\tests\Utils;

use App\Utils\DateControl;
use PHPUnit\Framework\TestCase;

class DateControlTest extends TestCase
{
    // Past dates should be forbidden
    public function testIsDateValidPastDate(){
      $dateControl = new DateControl();
      $pastDate = new \DateTime("09/24/2018");
      $result = $dateControl->isDateValid($pastDate);

      $this->assertEquals(false, $result);
    }

    // Hollydays should be forbidden
    public function testIsDateValidFutureChristmas(){
      $dateControl = new DateControl();
      $christmas = new \DateTime("12/25/2025");
      $result = $dateControl->isDateValid($christmas);

      $this->assertEquals(false, $result);
    }

    // Future non-hollyday should be allowed
    public function testIsDateValidFutureNonHollyDay(){
      $dateControl = new DateControl();
      $futureNonHollyDay = new \DateTime("12/24/2025");
      $result = $dateControl->isDateValid($futureNonHollyDay);

      $this->assertEquals(true, $result);
    }

    public function testAllowFullDayNotToday() {
      $dateControl = new DateControl();
      $notToday= new \DateTime("12/24/2024");
      $result = $dateControl->allowFullDay($notToday);

      $this->assertEquals(true, $result);
    }
}