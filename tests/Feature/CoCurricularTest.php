<?php

use Carbon\Carbon;
use FredBradley\SOCS\CoCurricular;
use FredBradley\SOCS\Config;
use FredBradley\SOCS\ReturnObjects\Club;
use FredBradley\SOCS\ReturnObjects\Event;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->config = new Config();
});

it('has a config', function () {
    expect($this->config)->toBeInstanceOf(Config::class)
        ->and($this->config->socsId)->toBeInt()
        ->and($this->config->apiKey)->toBeString();
});

it('can load cocurricular', function () {
    $socs = new CoCurricular($this->config);
    expect($socs)->toBeInstanceOf(CoCurricular::class);
});
it('has clubs', function () {
    $socs = new CoCurricular($this->config);
    $clubs = $socs->getClubs();
    expect($clubs)->toBeInstanceOf(Collection::class)
        ->and($clubs->first())->toBeInstanceOf(Club::class);
});
it('has events', function () {
    $socs = new CoCurricular($this->config);
    $events = $socs->getEvents(Carbon::now()->subDays(14), Carbon::now()->subDays(7));
    expect($events)->toBeInstanceOf(Collection::class)
        ->and($events->first())->toBeInstanceOf(Event::class);
});
it('has registers', function () {
    $socs = new CoCurricular($this->config);
    $registers = $socs->getRegisters(Carbon::now()->subDays(14));
    expect($registers)->toBeInstanceOf(Collection::class);
});

it('can get event by id', function () {
    $socs = new CoCurricular($this->config);
    $events = $socs->getEvents(Carbon::now()->subDays(300), Carbon::now()->addDays(7));
    $event = $socs->getEventById($events->first()->eventid);

    expect($event)->toBeInstanceOf(Event::class);
});

it('can get club by id', function () {
    $socs = new CoCurricular($this->config);
    $clubs = $socs->getClubs();
    $club = $socs->getClubById($clubs->first()->clubId);
    expect($club)->toBeInstanceOf(Club::class);
});

it('can get registration data for event', function () {
    $socs = new CoCurricular($this->config);
    $events = $socs->getEvents(Carbon::now()->subDays(14), Carbon::now()->addDays(7));
    $event = $socs->getEventById($events->first()->eventid);
    $register = $socs->getRegistrationDataForEvent(Carbon::now(), $event);
    expect($register)->toBeInstanceOf(Event::class)
        ->and($register->register)->toBeInstanceOf(Collection::class);
});

it('can get all registers', function () {
    $socs = new CoCurricular($this->config);
    $registers = $socs->getRegisters(Carbon::now()->subDays(14));
    expect($registers)->toBeInstanceOf(Collection::class);
});

test('example', function () {
    expect(true)->toBeTrue();
});
