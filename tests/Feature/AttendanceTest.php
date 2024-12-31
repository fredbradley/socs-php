<?php

use FredBradley\SOCS\Enums\Attendance;

it('can get bootstrap class for each attendance type', function () {
    expect(Attendance::ATTENDED->bootstrapClass())->toBe('success')
        ->and(Attendance::ABSENT->bootstrapClass())->toBe('danger')
        ->and(Attendance::AUTHORISED->bootstrapClass())->toBe('warning')
        ->and(Attendance::NOT_SET->bootstrapClass())->toBe('default');
});

it('can get value for each attendance type', function () {
    expect(Attendance::ATTENDED->getValue())->toBe('Attended')
        ->and(Attendance::ABSENT->getValue())->toBe('Absent')
        ->and(Attendance::AUTHORISED->getValue())->toBe('Authorised Absent')
        ->and(Attendance::NOT_SET->getValue())->toBe('Not Set');
});

it('can get label for each attendance type', function () {
    expect(Attendance::ATTENDED->label())->toBe('<span class="label label-success">Attended</span>')
        ->and(Attendance::ABSENT->label())->toBe('<span class="label label-danger">Absent</span>')
        ->and(Attendance::AUTHORISED->label())->toBe('<span class="label label-warning">Authorised Absent</span>')
        ->and(Attendance::NOT_SET->label())->toBe('<span class="label label-default">Not Set</span>');
});
