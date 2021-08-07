<?php

namespace FredBradley\SOCS;

use Carbon\CarbonInterface;

/**
 * Class Sport
 * @package FredBradley\SOCS
 */
class Sport extends SOCS
{
    /**
     * @var string
     */
    public string $baseUri = 'https://www.schoolssports.com/school/xml/';

    /**
     * Note: if team sheets are included the feed will only show fixtures for
     * the previous and next 7 days regardless of any date ranges defined.
     *
     * @param  \Carbon\CarbonInterface  $startDate
     * @param  \Carbon\CarbonInterface  $endDate
     * @param  bool  $withUnpublishedTeamSheets
     * @param  bool  $withTeamSheets
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
            $query = [
                'TS' => 1,
                'P' => 1,
            ];
        } elseif ($withTeamSheets) {
            $query = [
                'TS' => 1,
            ];
        }

        $query[ 'startdate' ] = $startDate->format(self::DATE_STRING);
        $query[ 'enddate' ] = $endDate->format(self::DATE_STRING);
        $options = $this->loadQuery($query);

        return $this->getResponse('fixturecalendar.ashx', ['query' => $options]);
    }

    /**
     * @param  \Carbon\CarbonInterface  $startDate
     * @param  \Carbon\CarbonInterface  $endDate
     *
     * @return false|\SimpleXMLElement|string|null
     */
    public function getStandardResults(
        CarbonInterface $startDate,
        CarbonInterface $endDate
    ) {
        $query[ 'startdate' ] = $startDate->format(self::DATE_STRING);
        $query[ 'enddate' ] = $endDate->format(self::DATE_STRING);
        $options = $this->loadQuery($query);

        return $this->getResponse('results.ashx', ['query' => $options]);
    }

    /**
     * @return false|\SimpleXMLElement|string|null
     */
    public function getTeams()
    {
        $query[ 'data' ] = 'teams';

        $options = $this->loadQuery($query);

        return $this->getResponse('mso-sport.ashx', ['query' => $options]);
    }

    /**
     * @param  \Carbon\CarbonInterface  $startDate
     * @param  \Carbon\CarbonInterface  $endDate
     * @param  bool  $withUnpublishedTeamSheets
     *
     * @return false|\SimpleXMLElement|string|null
     */
    public function getFixturesAndResults(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        bool $withUnpublishedTeamSheets = false
    ) {
        if ($withUnpublishedTeamSheets) {
            $query[ 'P' ] = 1;
        }
        $query[ 'data' ] = 'fixtures';
        $query[ 'startdate' ] = $startDate->format(self::DATE_STRING);
        $query[ 'enddate' ] = $endDate->format(self::DATE_STRING);
        $options = $this->loadQuery($query);

        return $this->getResponse('mso-sport.ashx', ['query' => $options]);
    }
}
