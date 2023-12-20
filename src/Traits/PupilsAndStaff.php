<?php

namespace FredBradley\SOCS\Traits;

use Illuminate\Support\Collection;

trait PupilsAndStaff
{
    /**
     * @var string|Collection<string>|\stdClass|int
     */
    public string|Collection|\stdClass|int $pupils;

    /**
     * @var string|Collection<string>|\stdClass|int
     */
    public string|Collection|\stdClass|int $staff;

    public function setPupilsAndStaff(\stdClass $object)
    {
        $this->pupils = 'Not Requested';
        $this->staff = 'Not Requested';

        if (property_exists($object, 'pupils')) {
            $this->setPupils($object);
        }
        if (property_exists($object, 'staff')) {
            $this->setStaff($object);
        }
    }

    private function setStaff(\stdClass $club): void
    {
        $this->staff = collect();
        if (is_string($club->staff)) {
            $this->staff = collect(explode(',', $club->staff));
        }
    }

    private function setPupils(\stdClass $club): void
    {
        $this->pupils = collect();
        if (is_string($club->pupils)) {
            $this->pupils = collect(explode(',', $club->pupils));
        }
    }
}
