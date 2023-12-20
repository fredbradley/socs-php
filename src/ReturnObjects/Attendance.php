<?php

namespace FredBradley\SOCS\ReturnObjects;

enum Attendance: string
{
    case ATTENDED = 'attended';
    case ABSENT = 'absent';
    case AUTHORISED = 'authorised absent';
    case NOT_SET = '';

    public function bootstrapClass(): string
    {
        return match ($this) {
            self::ATTENDED => 'success',
            self::ABSENT => 'danger',
            self::AUTHORISED => 'warning',
            default => 'default',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::ATTENDED => '<span class="label label-'.$this->bootstrapClass().'">Attended</span>',
            self::ABSENT => '<span class="label label-'.$this->bootstrapClass().'">Absent</span>',
            self::AUTHORISED => '<span class="label label-'.$this->bootstrapClass().'">Authorised Absent</span>',
            default => '<span class="label label-'.$this->bootstrapClass().'">Not Set</span>',
        };
    }
}
