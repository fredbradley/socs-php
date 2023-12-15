<?php

namespace FredBradley\SOCS\ReturnObjects;

class Club
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
        $this->clubName = trim(html_entity_decode($club->clubname));
        $this->gender = $club->gender;
        $this->yearGroups = $this->getYearGroups($club->yeargroups);
        if (property_exists($club, 'pupils')) {
            $this->setPupils($club);
        } else {
            $this->pupils = 'Not Requested';
        }
        if (property_exists($club, 'staff')) {
            $this->setStaff($club);
        } else {
            $this->staff = 'Not Requested';
        }
    }

    private function setStaff(\stdClass $club): void
    {
        if (is_string($club->staff)) {
            $this->staff = explode(',', $club->staff);
        } else {
            $this->staff = 0;
        }
    }

    private function setPupils(\stdClass $club): void
    {
        if (is_string($club->pupils)) {
            $this->pupils = explode(',', $club->pupils);
        } else {
            $this->pupils = 0;
        }
    }

    /**
     * @return \Illuminate\Support\Collection|string
     *
     * @psalm-return 'all'|\Illuminate\Support\Collection<int, int>
     */
    private function getYearGroups(string $yearGroups): \Illuminate\Support\Collection|string
    {
        if ($yearGroups === 'all') {
            return $yearGroups;
        }

        $collection = collect(explode(',', $yearGroups))->map(function ($item) {
            return (int) $item;
        });

        return $collection;
    }
}
