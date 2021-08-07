<?php

namespace FredBradley\SOCS\Tests;

use Carbon\Carbon;
use FredBradley\SOCS\CoCurricular;

class CoCurricularTest extends BaseTest
{
    /** @test */
    public function getClubs()
    {
        $socs = new CoCurricular($this->config);
        $result = $socs->getClubs();
        $this->isIterableXmlObject($result);
        $this->assertObjectHasAttribute('club', $result);
    }

    /** @test */
    public function getEvents()
    {
        $socs = new CoCurricular($this->config);
        $result = $socs->getEvents($this->startDate, $this->endDate);
        $this->isIterableXmlObject($result);
        $this->assertObjectHasAttribute('event', $result);
    }

    /** @test */
    public function getRegisters()
    {
        $socs = new CoCurricular($this->config);
        $result = $socs->getRegisters(Carbon::now()->addMonths(2));
        $this->isIterableXmlObject($result);
        $this->assertObjectHasAttribute('pupil', $result);
    }
}
