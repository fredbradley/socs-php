<?php

use Carbon\Carbon;
use FredBradley\SOCS\Config;
use FredBradley\SOCS\ReturnObjects\MusicLesson;
use FredBradley\SOCS\Tuition;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->config = new Config(
        socsId: (int) $_ENV['SOCSID'],
        apiKey: $_ENV['SOCSAPIKEY']
    );
});
it('is using tuition', function () {
    $socs = new Tuition($this->config);
    expect($socs)->toBeInstanceOf(Tuition::class);
});

it('gets staff pupil relationships', function () {
    $socs = new Tuition($this->config);
    $relationships = $socs->getRelationships('musiclessons');
    expect($relationships)->toBeInstanceOf(Collection::class);
});

it('can get music lessons', function () {
    $socs = new Tuition($this->config);
    $lessons = $socs->getMusicLessons(Carbon::now()->subDays(30));
    expect($lessons)->toBeInstanceOf(Collection::class);

    if ($lessons->isNotEmpty()) {
        expect($lessons->first())->toBeInstanceOf(MusicLesson::class);
    } else {
        expect($lessons->first())->toBeNull();
    }
});
it('throws exception when dodgy feed is presented', function () {
    $socs = new Tuition($this->config);
    $lessons = $socs->getrubbishfeed(Carbon::now()->subDays(14));
})->throws(Exception::class);

it('can deal with a null date', function () {
    $socs = new Tuition($this->config);
    $lessons = $socs->getMusicLessons(null);
    expect($lessons)->toBeInstanceOf(Collection::class);
});


it('can mutate', function () {

    $lesson = [
        'pupilid' => '3e4r4e38ew9',
        'staffid' => '3e4r4e38ew9',
        'startdate' => '01/12/2025',
        'starttime' => '12:00',
        'enddate' => '01/12/2025',
        'endtime' => '13:00',
        'instrument' => 'Test Instrument',
        'title' => 'Test Title',
        'location' => 'Test Location',
        'attendance' => 'Test Attendance',
    ];

    $musicLesson = new \FredBradley\SOCS\ReturnObjects\MusicLesson($lesson);

    expect($musicLesson->pupilId)->toBeString()
        ->and($musicLesson->staffId)->toBeString()
        ->and($musicLesson->startTime)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($musicLesson->endTime)->toBeInstanceOf(\Carbon\Carbon::class)
        ->and($musicLesson->instrument)->toBeString()
        ->and($musicLesson->title)->toBeString()
        ->and($musicLesson->location)->toBeString()
        ->and($musicLesson->attendance)->toBeString();
});
