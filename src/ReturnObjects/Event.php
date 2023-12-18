<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use Illuminate\Support\Collection;
use SimpleXMLElement;

final class Event extends ReturnObject
{
    public int $eventid;

    /**
     * @var Collection<string,SimpleXMLElement|string>|null
     */
    public ?Collection $register = null;

    public function __construct(\stdClass $event)
    {
        foreach (get_object_vars($event) as $key => $value) {
            if ($key === 'eventid') {
                $this->{$key} = (int) $value;
            } else {
                $this->{$key} = $value;
            }
        }
    }
}
