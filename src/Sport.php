<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use Carbon\CarbonInterface;
use FredBradley\SOCS\ReturnObjects\Fixture;

/**
 * Class Sport
 */
final class Sport extends SOCS
{
    public string $baseUri = 'https://www.schoolssports.com/school/xml/';

    /**
     * Note: if team sheets are included the feed will only show fixtures for
     * the previous and next 7 days regardless of any date ranges defined.
     */
    public function getStandardFixtures(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        bool $withUnpublishedTeamSheets = false,
        bool $withTeamSheets = false
    ): false|\SimpleXMLElement|string|null {
        $query = [];
        if ($withUnpublishedTeamSheets) {
            // @todo
            $query['P'] = 1;
        } elseif ($withTeamSheets) {
            // @todo
            $query['P'] = 2;
        }

        $query['startdate'] = $startDate->format(self::DATE_STRING);
        $query['enddate'] = $endDate->format(self::DATE_STRING);
        $options = $this->loadQuery($query);

        return $this->getResponse('fixturecalendar.ashx', ['query' => $options]);
    }

    public function getStandardResults(
        CarbonInterface $startDate,
        CarbonInterface $endDate
    ): false|\SimpleXMLElement|string|null {
        $query = [];
        $query['startdate'] = $startDate->format(self::DATE_STRING);
        $query['enddate'] = $endDate->format(self::DATE_STRING);
        $options = $this->loadQuery($query);

        return $this->getResponse('results.ashx', ['query' => $options]);
    }

    public function getTeams(): false|\SimpleXMLElement|string|null
    {
        $query = [];
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
        $query = [];
        if ($withUnpublishedTeamSheets) {
            $query['P'] = 1;
        }
        $query['data'] = 'fixtures';
        $query['startdate'] = $startDate->format(self::DATE_STRING);
        $query['enddate'] = $endDate->format(self::DATE_STRING);
        $options = $this->loadQuery($query);

        $response = $this->getResponse('mso-sport.ashx', ['query' => $options]);

        return collect(collect($response->values()['fixtures'])['fixture'])->mapInto(Fixture::class);
    }
}
