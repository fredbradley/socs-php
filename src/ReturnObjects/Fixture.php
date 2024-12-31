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

    public function __construct(\stdClass|array $fixture)
    {
        $fixture = (object) $fixture;

        $this->eventId = (int) $fixture->eventid;
        $this->teamId = (int) $fixture->teamid;
        $this->sport = $fixture->sport;
        $this->matchType = $fixture->matchtype;
        $this->opposition = $fixture->opposition;
        $this->oppositionTeam = $fixture->oppositionteam;
        $this->dateTime = $this->getDateTime($fixture->date, $fixture->time);
        if (isset($fixture->pupils)) {
            $this->pupils = array_filter(explode(',', $fixture->pupils));
        }
        $this->location = $fixture->location;
        $this->locationDetails = $fixture->locationdetails;
        $this->latLng = $fixture->latlng;
        $this->pointsFor = (int) $fixture->pointsfor;
        $this->pointsAgainst = (int) $fixture->pointsagainst;

        $this->winner = $fixture->winner;
        $this->result = $fixture->result;
        $this->details = $fixture->details;
        $this->url = $fixture->url;

    }

    private function getDateTime(string $date, string $time): Carbon|false
    {
        $date = Carbon::createFromFormat('d/m/Y', $date)->toDateString();
        $time = Carbon::createFromFormat('G:i', $time)->toTimeString();

        return Carbon::createFromFormat('Y-m-d G:i:s', $date.' '.$time);
    }
}
