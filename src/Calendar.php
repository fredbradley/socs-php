<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use Carbon\CarbonInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Saloon\XmlWrangler\Exceptions\QueryAlreadyReadException;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Saloon\XmlWrangler\XmlReader;
use Throwable;

/**
 * Class Calendar
 */
final class Calendar extends SOCS
{
    /**
     * @return Collection<array-key, object>
     *
     * @throws GuzzleException
     */
    public function getCalendar(
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        bool $withSport = true,
        bool $withCoCurricular = true,
        bool $withSchoolCalendar = true
    ): Collection {
        $query = [];
        foreach (compact('withCoCurricular', 'withSchoolCalendar', 'withSport') as $key => $option) {
            if ($option === false) {
                $query[$key] = 0;
            }
        }
        $query['startdate'] = $startDate->format(self::DATE_STRING);
        $query['enddate'] = $endDate->format(self::DATE_STRING);

        $options = $this->loadQuery($query);

        $response = $this->getResponse('SOCScalendar.ashx', ['query' => $options]);

        return $this->collectionOfEvents($response);
    }

    /**
     * @return Collection<array-key, object>
     *
     * @throws QueryAlreadyReadException
     * @throws XmlReaderException|Throwable
     */
    private function collectionOfEvents(XmlReader $response): Collection
    {
        return $response->value('CalendarEvent')
            ->collect()
            ->map(function (array $calendarEvent) {
                return (object) $calendarEvent;
            });
    }
}
