<?php

declare(strict_types=1);

namespace FredBradley\SOCS\Traits;

use Illuminate\Support\Collection;

trait PupilsAndStaff
{
    /**
     * @var string|Collection<array-key,string>
     */
    public string|Collection $pupils;

    /**
     * @var string|Collection<array-key,string>
     */
    public string|Collection $staff;

    /**
     * @param  array<string,mixed>  $object
     */
    public function setPupilsAndStaff(array $object): void
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

    /**
     * @param  array<string,mixed>  $club
     */
    private function setPupils(array $club): void
    {
        $this->pupils = collect(explode(',', $club['pupils']));
        if (empty($club['pupils'])) {
            $this->pupils = collect();
        }
    }

    /**
     * @param  array<string,mixed>  $club
     */
    private function setStaff(array $club): void
    {
        $this->staff = collect(explode(',', $club['staff']));

        if (empty($club['staff'])) {
            $this->staff = collect();
        }
    }
}
