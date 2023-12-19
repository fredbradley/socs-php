<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use FredBradley\SOCS\Traits\PupilsAndStaff;
use Illuminate\Support\Collection;

final class Club extends ReturnObject
{
    use PupilsAndStaff;

    public string $term;

    public int $academicYear;

    public string $category;

    public int $clubId;

    public string $clubName;

    public string $gender;

    public mixed $yearGroups;

    /**
     * @var string|array<string>|int
     */
    public string|array|int $pupils;

    /**
     * @var string|array<string>|int
     */
    public string|array|int $staff;

    public function __construct(\stdClass $club)
    {
        $this->term = $club->term;
        $this->academicYear = (int) $club->academicyear;
        $this->category = $club->category;
        $this->clubId = (int) $club->clubid;
        $this->clubName = $this->getClubName($club->clubname);
        $this->gender = $club->gender;
        $this->yearGroups = $this->getYearGroups($club->yeargroups);

        $this->setPupilsAndStaff($club);
    }

    private function getClubName(string $clubName): string
    {
        return trim(html_entity_decode($clubName));
    }

    /**
     * @return Collection<array-key, int>|string
     */
    private function getYearGroups(string $yearGroups): Collection|string
    {
        if ($yearGroups === 'all') {
            return $yearGroups;
        }

        return collect(explode(',', $yearGroups))->map(function ($item) {
            return (int) $item;
        });
    }
}
