<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use Carbon\Carbon;

final class MusicLesson
{
    public string $pupilId;

    public string $staffId;

    public Carbon $startTime;

    public Carbon $endTime;

    public string $instrument;

    public string $title;

    public string $location;

    public mixed $attendance;

    public function __construct(\stdClass $lesson)
    {
        $this->pupilId = $lesson['pupilid'];
        $this->staffId = $lesson['staffid'];
        $this->startTime = Carbon::parse($this->convertDate($lesson['startdate']).' '.$lesson['starttime']);
        $this->endTime = Carbon::parse($this->convertDate($lesson['enddate']).' '.$lesson['endtime']);
        $this->instrument = $lesson['instrument'];
        $this->title = $lesson['title'];
        $this->location = $lesson['location'];
        $this->attendance = $lesson['attendance'];
    }

    private function convertDate(string $date): string
    {
        $dateBits = explode('/', $date);

        return $dateBits[2].'-'.$dateBits[1].'-'.$dateBits[0];
    }
}
