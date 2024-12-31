<?php

use Carbon\Carbon;
use FredBradley\SOCS\Config;
use FredBradley\SOCS\Tuition;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->config = new Config(
        socsId: (int) $_ENV['SOCSID'],
        apiKey: $_ENV['SOCSAPIKEY']
    );
    $this->tuition = new Tuition($this->config);
});

it('can get music lessons', function () {
    $lessons = $this->tuition->getMusicLessons(Carbon::now()->subDays(30));
    expect($lessons)->toBeInstanceOf(Collection::class);
    if ($lessons->isNotEmpty()) {
        expect($lessons->first())->toBeInstanceOf(\FredBradley\SOCS\ReturnObjects\MusicLesson::class);
    }
});

it('can get sport coaching', function () {
    $coaching = $this->tuition->getSportCoaching(Carbon::now()->subDays(30));
    expect($coaching)->toBeInstanceOf(Collection::class);
});

it('can get academic tutoring', function () {
    $tutoring = $this->tuition->getAcademicTutoring(Carbon::now()->subDays(30));
    expect($tutoring)->toBeInstanceOf(Collection::class);
});

it('can get performing arts', function () {
    $arts = $this->tuition->getPerformingArts(Carbon::now()->subDays(30));
    expect($arts)->toBeInstanceOf(Collection::class);
});

it('throws exception for invalid method', function () {
    expect(fn () => $this->tuition->invalidMethod())->toThrow(Exception::class);
});

it('can get relationships', function () {
    $relationships = $this->tuition->getRelationships('musiclessons');
    expect($relationships)->toBeInstanceOf(Collection::class);
    expect($relationships->isNotEmpty())->toBeTrue();
});

it('throws exception for invalid type in getRelationships', function () {
    expect(fn () => $this->tuition->getRelationships('invalidtype'))
        ->toThrow(Exception::class, 'Method not allowed');
});

it('gets relationships for valid type', function () {
    $validTypes = ['musiclessons', 'sportcoaching', 'academictutoring', 'performingarts'];
    foreach ($validTypes as $type) {
        $relationships = $this->tuition->getRelationships($type);
        expect($relationships)->toBeInstanceOf(Collection::class);
    }
});
it('throws exception for invalid method in __call', function () {
    expect(fn () => $this->tuition->invalidMethod())->toThrow(Exception::class, 'Method not allowed');
});
