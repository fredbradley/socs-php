<?php

namespace FredBradley\SOCS\ReturnObjects;

use Carbon\Carbon;

class MusicLesson
{
    public $pupilId;
    public $staffId;
    public $startTime;
    public $endTime;
    public $instrument;
    public $title;
    public $location;
    public $attendance;

    public function __construct($lesson)
    {
        $this->pupilId = $lesson['pupilid'];
        $this->staffId = $lesson['staffid'];
        $this->startTime = Carbon::parse($lesson['startdate'].' '.$lesson['starttime']);
        $this->endTime = Carbon::parse($lesson['startdate'].' '.$lesson['endtime']);
        $this->instrument = $lesson['instrument'];
        $this->title = $lesson['title'];
        $this->location = $lesson['location'];
        $this->attendance = $lesson['attendance'];
    }
}
