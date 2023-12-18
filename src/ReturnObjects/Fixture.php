<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use Carbon\Carbon;

final class Fixture
{
    public int $eventId;

    public int $teamId;

    public string $sport;

    public string $matchType;

    public string $opposition;

    public string $oppositionTeam;

    public Carbon $dateTime;

    public string $location;

    public string $locationDetails;

    public string $latLng;

    public int $pointsFor;

    public int $pointsAgainst;

    public string $winner;

    public string $result;

    public string $details;

    public string $url;

    /**
     * @var array<string>
     */
    public array $pupils;

    public function __construct(\stdClass $fixture)
    {
        $this->eventId = $fixture->eventid;
        $this->teamId = $fixture->teamid;
        $this->sport = $fixture->sport;
        $this->matchType = $fixture->matchtype;
        $this->opposition = $fixture->opposition;
        $this->oppositionTeam = $fixture->oppositionteam;
        $this->dateTime = $this->getDateTime($fixture->date, $fixture->time);
    }

    private function getDateTime(string $date, string $time): Carbon|false
    {
        //        return $date." ".$time;
        $date = Carbon::createFromFormat('d/m/Y', $date)->toDateString();
        $time = Carbon::createFromFormat('G:i', $time)->toTimeString();

        return Carbon::createFromFormat('Y-m-d G:i:s', $date.' '.$time);
    }
}
