<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use FredBradley\SOCS\Traits\PupilsAndStaff;
use Illuminate\Support\Collection;

final class Event extends ReturnObject
{
    use PupilsAndStaff;

    public int $eventid;

    public int $clubid;

    public string $title;

    public string $location;

    public string $startdate;

    /**
     * @var Collection<array-key, mixed>
     */
    public Collection $register;

    /**
     * @param  array<string,mixed>  $event
     */
    public function __construct(array $event)
    {
        foreach ($event as $key => $value) {
            if (in_array($key, ['eventid', 'clubid'])) {
                $this->{$key} = (int) $value;
            } elseif ($key === 'title') {
                $this->{$key} = $this->getEventTitle($value);
            } else {
                $this->{$key} = $value;
            }
        }
        $this->setPupilsAndStaff($event);
    }

    /**
     * This feels really hacky, but we believe it's the only way.
     * SOCS's XML feed doesn't encode the ampersand properly,
     * resulting in '&amp;amp;' appearing in the XML, which
     * we can't decode properly. So here we are replacing
     * '&amp;' with '+' (and then trimming the result).
     */
    private function getEventTitle(string $title): string
    {
        $title = str_replace('&amp;', '+', $title);

        return trim($title);
    }
}
