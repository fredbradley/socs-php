<?php
it('can mutate an event array', function () {
    $array = [
        'eventid' => '1',
        'clubid' => '1',
        'title' => 'Test Event',
        'location' => 'Test Location',
        'startdate' => '01/01/2022',
    ];
    $event = new \FredBradley\SOCS\ReturnObjects\Event($array);
    expect($event->eventid)->toBeInt()
        ->and($event->clubid)->toBeInt()
        ->and($event->title)->toBeString()
        ->and($event->location)->toBeString()
        ->and($event->startdate)->toBeString();
});
it('properly deals with ampersands', function() {
    $array = [
        'eventid' => '1',
        'clubid' => '1',
        'title' => 'Test &amp; Event',
        'location' => 'Test Location',
        'startdate' => '01/01/2022',
    ];
    $event = new \FredBradley\SOCS\ReturnObjects\Event($array);
    expect($event->title)->toBeString()->toEqual('Test + Event');
});
