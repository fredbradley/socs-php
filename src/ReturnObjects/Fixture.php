<?php

namespace FredBradley\SOCS\ReturnObjects;

use Carbon\Carbon;

class Fixture
{
    public $eventId;

    public $teamId;

    public $sport;

    public $matchType;

    public $opposition;

    public $oppositionTeam;

    public $dateTime;

    public $location;

    public $locationDetails;

    public $latLng;

    public $pointsFor;

    public $pointsAgainst;

    public $winner;

    public $result;

    public $details;

    public $url;

    public $pupils;

    public function __construct($fixture)
    {
        $this->eventId = $fixture['eventid'];
        $this->teamId = $fixture['teamid'];
        $this->sport = $fixture['sport'];
        $this->matchType = $fixture['matchtype'];
        $this->opposition = $fixture['opposition'];
        $this->oppositionTeam = $fixture['oppositionteam'];
        $this->dateTime = $this->getDateTime($fixture['date'], $fixture['time']);
    }

    private function getDateTime(string $date, string $time)
    {
        //        return $date." ".$time;
        $date = Carbon::createFromFormat('d/m/Y', $date)->toDateString();
        $time = Carbon::createFromFormat('G:i', $time)->toTimeString();

        return Carbon::createFromFormat('Y-m-d G:i:s', $date.' '.$time);
    }
}
