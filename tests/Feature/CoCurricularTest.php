<?php
beforeEach(function () {
    $this->config = new \FredBradley\SOCS\Config();
});

it('has a config', function () {
    expect($this->config)->toBeInstanceOf(\FredBradley\SOCS\Config::class);
});
it('can load', function () {
    $socs = new \FredBradley\SOCS\CoCurricular($this->config);
    expect($socs)->toBeInstanceOf(\FredBradley\SOCS\CoCurricular::class);
});
it('has clubs', function () {
    $socs = new \FredBradley\SOCS\CoCurricular($this->config);
    $clubs = $socs->getClubs();
    expect($clubs)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($clubs->first())->toBeInstanceOf(\FredBradley\SOCS\ReturnObjects\Club::class);
});
it('has events', function () {
    $socs = new \FredBradley\SOCS\CoCurricular($this->config);
    $events = $socs->getEvents(\Carbon\Carbon::now()->subDays(14), \Carbon\Carbon::now()->subDays(7));
    expect($events)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($events->first())->toBeInstanceOf(\FredBradley\SOCS\ReturnObjects\Event::class);
});
it('has registers', function () {
    $socs = new \FredBradley\SOCS\CoCurricular($this->config);
    $registers = $socs->getRegisters(\Carbon\Carbon::now()->subDays(14));
    expect($registers)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

it('can get event by id', function () {
    $socs = new \FredBradley\SOCS\CoCurricular($this->config);
    $events = $socs->getEvents(\Carbon\Carbon::now()->subDays(14), \Carbon\Carbon::now()->subDays(7));

    $event = $socs->getEventById($events->first()->eventid);
    dd($event);
    expect($event)->toBeInstanceOf(\FredBradley\SOCS\ReturnObjects\Event::class);
});
it('can get club by id', function () {
    $socs = new \FredBradley\SOCS\CoCurricular($this->config);
    $clubs = $socs->getClubs();
    $club = $socs->getClubById($clubs->first()->clubId);
    expect($club)->toBeInstanceOf(\FredBradley\SOCS\ReturnObjects\Club::class);
});

it('can get registration data for event', function () {
    $socs = new \FredBradley\SOCS\CoCurricular($this->config);
    $events = $socs->getEvents(\Carbon\Carbon::now()->subDays(14), \Carbon\Carbon::now()->addDays(7));
    $event = $socs->getEventById($events->first()->eventid);
    $register = $socs->getRegistrationDataForEvent(\Carbon\Carbon::now(), $event);
    expect($register)->toBeInstanceOf(\FredBradley\SOCS\ReturnObjects\Event::class);
    expect($register->register)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
it('can get all registers', function () {
    $socs = new \FredBradley\SOCS\CoCurricular($this->config);
    $registers = $socs->getRegisters(\Carbon\Carbon::now()->subDays(14));
    expect($registers)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});


test('example', function () {
    expect(true)->toBeTrue();
});

