<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

use FredBradley\SOCS\Traits\PupilsAndStaff;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class Club extends ReturnObject
{
    use PupilsAndStaff;

    public string $term;

    public int $academicYear;

    public string $yearText;

    public string $category;

    public int $clubId;

    public string $clubName;

    public string $gender;

    public Collection $times;

    public string $venue;

    /**
     * @var string|Collection<array-key,int>
     */
    public string|Collection $yearGroups;

    /**
     * @param  array<array-key,string>  $club
     */
    public function __construct(array $club)
    {
        $this->term = $club['term'];
        $this->academicYear = (int) $club['academicyear'];
        $this->yearText = $this->getYearText();
        $this->category = $club['category'];
        $this->clubId = (int) $club['clubid'];
        $this->clubName = $this->getClubName($club['clubname']);
        $this->gender = $club['gender'];
        $this->yearGroups = $this->getYearGroups($club['yeargroups']);
        $this->setPupilsAndStaff($club);
        if (isset($club['defaultvenue'])) {
            $this->setPlanning($club);
        }
    }

    private function getYearText(): string
    {
        $year = $this->academicYear;

        return sprintf(
            '%d-%d',
            substr($year, 0, 4),
            substr($year, 4, 4)
        );

    }

    private function getClubName(string $clubName): string
    {
        return trim($clubName);
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

    /**
     * @param  array<array-key,string>  $club
     */
    private function setPlanning(array $club): void
    {
        $this->venue = $club['defaultvenue'];
        $this->times = collect();

        collect(array_keys($club))->filter(function ($value) {
            return Str::endsWith($value, 'time');
        })->each(function ($item) use ($club): void {
            $this->times->put(Str::before($item, 'time'), $club[$item]);
        });
        $this->times = $this->times->filter(function ($value) {
            return ! empty($value);
        });
    }
}
