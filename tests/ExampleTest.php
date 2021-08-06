<?php

namespace FredBradley\SOCS\Tests;

use FredBradley\SOCS\SOCS;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
    /** @test */
    public function gets_data()
    {
        $socs = new SOCS("SOCSID", "SOCSAPIKEY");
        $this->assertIsObject($socs->getClubs());
        $this->assertIsIterable($socs->getClubs());
        $this->assertObjectHasAttribute('club', $socs->getClubs());
        $this->assertThat($socs->getClubs(), self::isInstanceOf(\SimpleXMLElement::class));
    }
}
