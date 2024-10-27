<?php

use Carbon\Carbon;
use FredBradley\SOCS\CoCurricular;
use FredBradley\SOCS\Config;
use FredBradley\SOCS\ReturnObjects\Club;
use FredBradley\SOCS\ReturnObjects\Event;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->config = new Config(
        socsId: (int) $_ENV['SOCSID'],
        apiKey: $_ENV['SOCSAPIKEY']
    );
    $this->socs = new CoCurricular($this->config);
});

it('calculates todays date correctly', function () {
    $registers = $this->socs->getRegisters(null);
    expect($registers)->toBeInstanceOf(Collection::class);
});

it('has a config', function () {
    expect($this->config)->toBeInstanceOf(Config::class)
        ->and($this->config->socsId)->toBeInt()
        ->and($this->config->apiKey)->toBeString();
});

it('can load cocurricular', function () {
    expect($this->socs)->toBeInstanceOf(CoCurricular::class);
});
it('has clubs', function () {
    $clubs = $this->socs->getClubs();
    expect($clubs)->toBeInstanceOf(Collection::class)
        ->and($clubs->first())->toBeInstanceOf(Club::class);
});

it('has events', function () {
    $events = $this->socs->getEvents(Carbon::now()->subDays(14), Carbon::now()->subDays(7));
    expect($events)->toBeInstanceOf(Collection::class)
        ->and($events->first())->toBeInstanceOf(Event::class);
});

it('can get a specific event', function () {
    $events = $this->socs->getEvents(Carbon::now()->subDays(14), Carbon::now()->subDays(7));
    $event = $this->socs->getEventById(
        $events->first()->eventid,
        Carbon::createFromFormat('d/m/Y', $events->first()->startdate)
    );
    expect($event)->toBeInstanceOf(Event::class);
});

it('has registers', function () {
    $registers = $this->socs->getRegisters(Carbon::now()->subDays(14));
    expect($registers)->toBeInstanceOf(Collection::class);
});

it('can get club by id', function () {
    $clubs = $this->socs->getClubs();
    $club = $this->socs->getClubById($clubs->first()->clubId);
    expect($club)->toBeInstanceOf(Club::class);
});

it('can get all registers', function () {
    $registers = $this->socs->getRegisters(Carbon::now()->subDays(14));
    expect($registers)->toBeInstanceOf(Collection::class);
});
it('can set staff and pupils', function () {
    $clubs = $this->socs->getClubs(true, true, true);
    $club = $clubs->first();
    expect($club)->toBeInstanceOf(Club::class)
        ->and($club->staff)->toBeIterable()->and($club->pupils)->toBeIterable();

    $club->testproperty = 'HELLO WORLD';
    expect($club->testproperty)->toBeString()
        ->and($club->testproperty)->toBe('HELLO WORLD');
});
it('can deal with pupils being non iterable', function () {
    $registers = $this->socs->getRegisters(Carbon::parse('2021-09-01'));
    expect($registers)->toBeInstanceOf(Collection::class)->isEmpty();
});
