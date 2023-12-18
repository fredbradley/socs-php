<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use Illuminate\Support\Collection;

final class Club
{
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

        $this->pupils = 'Not Requested';
        $this->staff = 'Not Requested';

        if (property_exists($club, 'pupils')) {
            $this->setPupils($club);
        }
        if (property_exists($club, 'staff')) {
            $this->setStaff($club);
        }
    }

    private function getClubName(string $clubName): string
    {
        return trim(html_entity_decode($clubName));
    }

    private function setStaff(\stdClass $club): void
    {
        $this->staff = 0;
        if (is_string($club->staff)) {
            $this->staff = explode(',', $club->staff);
        }
    }

    private function setPupils(\stdClass $club): void
    {
        $this->pupils = 0;
        if (is_string($club->pupils)) {
            $this->pupils = explode(',', $club->pupils);
        }
    }

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
