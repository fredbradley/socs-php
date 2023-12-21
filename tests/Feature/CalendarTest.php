<?php

use Carbon\Carbon;
use FredBradley\SOCS\Config;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->config = new Config(
        socsId: (int) $_ENV['SOCSID'],
        apiKey: $_ENV['SOCSAPIKEY']
    );
});
it('is using calendar', function () {
    $socs = new \FredBradley\SOCS\Calendar($this->config);
    expect($socs)->toBeInstanceOf(\FredBradley\SOCS\Calendar::class);
});
it('can get events', function () {
    $socs = new \FredBradley\SOCS\Calendar($this->config);
    $events = $socs->getCalendar(Carbon::now()->subDays(7), Carbon::now()->addDays(7));
    expect($events)->toBeInstanceOf(Collection::class);
});
it('can get events and set booleans in the url', function () {
    $socs = new \FredBradley\SOCS\Calendar($this->config);
    $events = $socs->getCalendar(Carbon::now()->subDays(7), Carbon::now()->addDays(7), false, false, false);
    expect($events)->toBeInstanceOf(Collection::class);
});
