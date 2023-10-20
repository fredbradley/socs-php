<?php

namespace FredBradley\SOCS\Tests;

use Carbon\Carbon;
use FredBradley\SOCS\CoCurricular;
use Illuminate\Support\Collection;

class CoCurricularTest extends BaseTest
{
    /** @test */
    public function getClubs()
    {
        $socs = new CoCurricular($this->config);
        $result = $socs->getClubs();
        $this->assertInstanceOf(Collection::class, $result);
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
        $result = $socs->getRegisters(Carbon::parse('2021-10-7'));
        $this->isIterableXmlObject($result);
        $this->assertObjectHasAttribute('pupil', $result);
    }
}
