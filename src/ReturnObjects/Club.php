<?php

namespace FredBradley\SOCS\ReturnObjects;

class Club
{
    public $term;
    public $academicYear;
    public $category;
    public $clubId;
    public $clubName;
    public $gender;
    public $yearGroups;

    public function __construct($club)
    {
        $this->term = $club['term'];
        $this->academicYear = (int) $club['academicyear'];
        $this->category = $club['category'];
        $this->clubId = (int) $club['clubid'];
        $this->clubName = html_entity_decode($club['clubname']);
        $this->gender = $club['gender'];
        $this->yearGroups = $this->getYearGroups($club['yeargroups']);
    }

    /**
     * @param  string  $yearGroups
     *
     * @return \Illuminate\Support\Collection|string
     */
    private function getYearGroups(string $yearGroups)
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
