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

    /**
     * @param $lesson
     */
    public function __construct($lesson)
    {
        $this->pupilId = $lesson['pupilid'];
        $this->staffId = $lesson['staffid'];
        $this->startTime = Carbon::parse($this->convertDate($lesson['startdate']).' '.$lesson['starttime']);
        $this->endTime = Carbon::parse($this->convertDate($lesson['startdate']).' '.$lesson['endtime']);
        $this->instrument = $lesson['instrument'];
        $this->title = $lesson['title'];
        $this->location = $lesson['location'];
        $this->attendance = $lesson['attendance'];
    }

    /**
     * @param  string  $date
     *
     * @return string
     */
    private function convertDate(string $date)
    {
        $dateBits = explode("/", $date);

        return $dateBits[ 2 ].'-'.$dateBits[ 1 ].'-'.$dateBits[ 0 ];
    }
}
