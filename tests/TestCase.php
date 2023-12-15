<?php

namespace Tests;

use FredBradley\SOCS\Config;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $this->config = new Config();
        parent::setUp();

        // $this->withoutExceptionHandling();
    }
}
