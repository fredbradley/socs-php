<?php

namespace FredBradley\SOCS;

use Carbon\CarbonInterface;
use FredBradley\SOCS\ReturnObjects\Fixture;

/**
 * Class Sport
 */
class Sport extends SOCS
{
    public string $baseUri = 'https://www.schoolssports.com/school/xml/';

    /**
     * Note: if team sheets are included the feed will only show fixtures for
     * the previous and next 7 days regardless of any date ranges defined.
     *
     *
     * @return false|\SimpleXMLElement|string|null
     */
    public function getStandardFixtures(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        bool $withUnpublishedTeamSheets = false,
        bool $withTeamSheets = false
    ) {
        if ($withUnpublishedTeamSheets) {
        } elseif ($withTeamSheets) {
        }

        $query['startdate'] = $startDate->format(self::DATE_STRING);
        $query['enddate'] = $endDate->format(self::DATE_STRING);
        $options = $this->loadQuery($query);

        return $this->getResponse('fixturecalendar.ashx', ['query' => $options]);
    }

    /**
     * @return false|\SimpleXMLElement|string|null
     */
    public function getStandardResults(
        CarbonInterface $startDate,
        CarbonInterface $endDate
    ) {
        $query['startdate'] = $startDate->format(self::DATE_STRING);
        $query['enddate'] = $endDate->format(self::DATE_STRING);
        $options = $this->loadQuery($query);

        return $this->getResponse('results.ashx', ['query' => $options]);
    }

    /**
     * @return false|\SimpleXMLElement|string|null
     */
    public function getTeams()
    {
        $query['data'] = 'teams';

        $options = $this->loadQuery($query);

        return $this->getResponse('mso-sport.ashx', ['query' => $options]);
    }

    /**
     * @psalm-return \Illuminate\Support\Collection<array-key, Fixture>
     */
    public function getFixturesAndResults(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        bool $withUnpublishedTeamSheets = false
    ): \Illuminate\Support\Collection {
        if ($withUnpublishedTeamSheets) {
            $query['P'] = 1;
        }
        $query['data'] = 'fixtures';
        $query['startdate'] = $startDate->format(self::DATE_STRING);
        $query['enddate'] = $endDate->format(self::DATE_STRING);
        $options = $this->loadQuery($query);

        $response = $this->getResponse('mso-sport.ashx', ['query' => $options]);

        //return $response;
        return $this->recordsToCollection($response)->mapInto(Fixture::class);

    }
}
