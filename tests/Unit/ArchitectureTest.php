<?php

use FredBradley\SOCS\Config;

test('do not use env helper in codebase')
    ->expect('env')
    ->not->toBeUsed();

test('globals')
    ->expect([
        'dd',
        'dump',
        'ddd',
        'die',
        'ray',
        'sleep',
        'print_r',
        'echo',
        'exit',
        'print',
        'var_dump',
    ])
    ->toBeUsedInNothing();

test('SOCS is Abstract')
    ->expect(\FredBradley\SOCS\SOCS::class)
    ->toBeAbstract();

test('ReturnObject is Abstract')
    ->expect(\FredBradley\SOCS\ReturnObjects\ReturnObject::class)
    ->toBeAbstract();

test('All classes in ReturnObjects extend ReturnObject')
    ->expect('FredBradley\SOCS\ReturnObjects')
    ->toExtend(\FredBradley\SOCS\ReturnObjects\ReturnObject::class);

test('Config has variables')
    ->expect(Config::class)
    ->toBeClass()
    ->toHaveConstructor()
    ->toBeFinal();

test('Classes Extend SOCS')
    ->expect('FredBradley\SOCS')
    ->toExtend(\FredBradley\SOCS\SOCS::class)->ignoring([
        'FredBradley\SOCS\Traits',
        'FredBradley\SOCS\ReturnObjects',
        'FredBradley\SOCS\Config',
        \FredBradley\SOCS\Enums\Attendance::class,
    ]);
test('Traits are Traits')
    ->expect('FredBradley\SOCS\Traits')
    ->toBeTraits();

test('strict types used everywhere')
    ->expect('FredBradley\SOCS')
    ->toUseStrictTypes();
