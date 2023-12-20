<?php

namespace FredBradley\SOCS\Traits;

use Illuminate\Support\Collection;

trait PupilsAndStaff
{
    /**
     * @var string|Collection<string>
     */
    public string|Collection $pupils;

    /**
     * @var string|Collection<string>
     */
    public string|Collection $staff;

    public function setPupilsAndStaff(array $object)
    {
        $this->pupils = 'Not Requested';
        $this->staff = 'Not Requested';

        if (isset($object['pupils'])) {
            $this->setPupils($object);
        }
        if (isset($object['staff'])) {
            $this->setStaff($object);
        }
    }

    private function setStaff(array $club): void
    {
        $this->staff = collect(explode(',', $club['staff']));

        if (empty($club['staff'])) {
            $this->staff = collect();
        }
    }

    private function setPupils(array $club): void
    {
        $this->pupils = collect(explode(',', $club['pupils']));
        if (empty($club['pupils'])) {
            $this->pupils = collect();
        }
    }
}
