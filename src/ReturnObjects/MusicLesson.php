<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use Carbon\Carbon;

final class MusicLesson extends ReturnObject
{
    public string $pupilId;

    public string $staffId;

    public Carbon $startTime;

    public Carbon $endTime;

    public ?string $instrument;

    public string $title;

    public string $location;

    public string $attendance;

    /**
     * @param  array<array-key, string>  $lesson
     */
    public function __construct(array $lesson)
    {
        $lesson = (object) $lesson;
        $enddate = $lesson->enddate ?? $lesson->startdate;
        $this->pupilId = $lesson->pupilid;
        $this->staffId = $lesson->staffid;
        $this->startTime = Carbon::parse($this->convertDate($lesson->startdate).' '.$lesson->starttime);
        $this->endTime = Carbon::parse($this->convertDate($enddate).' '.$lesson->endtime);
        $this->instrument = property_exists($lesson, 'instrument') ? $lesson->instrument : null;
        $this->title = $lesson->title;
        $this->location = is_string($lesson->location) ? $lesson->location : '';
        $this->attendance = is_string($lesson->attendance) ? $lesson->attendance : '';
    }

    private function convertDate(string $date): string
    {
        $dateBits = explode('/', $date);

        return $dateBits[2].'-'.$dateBits[1].'-'.$dateBits[0];
    }
}
