<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use Illuminate\Support\Collection;

class Event extends AbstractObject
{
    public int $eventid;
    /**
     * @var Collection<string,\SimpleXMLElement|string>|null
     */
    public ?Collection $register = null;

    /**
     * @param array<string,string>|\stdClass $event
     */
    public function __construct(array|\stdClass $event)
    {
        if (is_array($event)) {
            foreach (array_keys($event) as $key) {
                $this->{$key} = $event[$key];
            }
        } else {
            foreach (get_object_vars($event) as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
