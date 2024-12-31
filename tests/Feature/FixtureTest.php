<?php

it('can mutate an fixture array', function () {
    $array = [
        'eventid' => '1',
        'teamid' => '1',
        'sport' => 'Test Sport',
        'matchtype' => 'Test Match Type',
        'opposition' => 'Test Opposition',
        'oppositionteam' => 'Test Opposition Team',
        'date' => '01/01/2022',
        'time' => '12:00',
        'location' => 'Test Location',
        'locationdetails' => 'Test Location Details',
        'latlng' => 'Test LatLng',
        'pointsfor' => '1',
        'pointsagainst' => '2',
        'winner' => 'Test Winner',
        'result' => 'Test Result',
        'details' => 'Test Details',
        'url' => 'Test URL',
        'pupils' => 'Test Pupil 1, Test Pupil 2',
    ];
    $fixture = new \FredBradley\SOCS\ReturnObjects\Fixture($array);
    expect($fixture->eventId)->toBeInt()
        ->and($fixture->teamId)->toBeInt()
        ->and($fixture->sport)->toBeString()
        ->and($fixture->matchType)->toBeString()
        ->and($fixture->opposition)->toBeString()
        ->and($fixture->oppositionTeam)->toBeString()
        ->and($fixture->dateTime)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($fixture->location)->toBeString()
        ->and($fixture->locationDetails)->toBeString()
        ->and($fixture->latLng)->toBeString()
        ->and($fixture->pointsFor)->toBeInt()
        ->and($fixture->pointsAgainst)->toBeInt()
        ->and($fixture->winner)->toBeString()
        ->and($fixture->result)->toBeString()
        ->and($fixture->details)->toBeString()
        ->and($fixture->url)->toBeString()
        ->and($fixture->pupils)->toBeArray();
});
