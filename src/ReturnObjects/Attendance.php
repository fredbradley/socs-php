<?php

namespace FredBradley\SOCS\ReturnObjects;

enum Attendance: string
{
    case ATTENDED = 'attended';
    case ABSENT = 'absent';
    case AUTHORISED = 'authorised absent';
    case NOT_SET = '';
}
