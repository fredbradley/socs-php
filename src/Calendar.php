<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use Carbon\CarbonInterface;

/**
 * Class Calendar
 */
class Calendar extends SOCS
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCalendar(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        bool $withSport = true,
        bool $withCoCurricular = true,
        bool $withSchoolCalendar = true
    ): false|\SimpleXMLElement|string|null {
        $query = [];
        foreach (compact('withCoCurricular', 'withSchoolCalendar', 'withSport') as $key => $option) {
            if ($option === false) {
                $query[$key] = 0;
            }
        }
        $query['startdate'] = $startDate->format(self::DATE_STRING);
        $query['enddate'] = $endDate->format(self::DATE_STRING);

        $options = $this->loadQuery($query);

        return $this->getResponse('SOCScalendar.ashx', ['query' => $options]);
    }
}
