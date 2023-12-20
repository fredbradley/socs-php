<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use FredBradley\SOCS\Traits\PupilsAndStaff;
use Illuminate\Support\Collection;
use SimpleXMLElement;

final class Event extends ReturnObject
{
    use PupilsAndStaff;

    public int $eventid;

    public int $clubid;

    /**
     * @var Collection<string,SimpleXMLElement|string>|null
     */
    public ?Collection $register = null;

    public function __construct(array $event)
    {
        foreach ($event as $key => $value) {
            if (in_array($key, ['eventid', 'clubid'])) {
                $this->{$key} = (int) $value;
            } else {
                $this->{$key} = $value;
            }
        }
        $this->setPupilsAndStaff($event);
    }
}
