<?php

namespace FredBradley\SOCS\Tests;

use Carbon\Carbon;
use FredBradley\SOCS\Calendar;

class CalendarTest extends BaseTest
{
    /** @test */
    public function getCalendar()
    {
        $socs = new Calendar($this->config);
        $result = $socs->getCalendar(Carbon::now()->subMonths(3), Carbon::now()->addMonths(3), true, true, true);

        $this->assertObjectHasAttribute('CalendarEvent', $result);
        $this->isIterableXmlObject($result);
    }
}
