<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use FredBradley\SOCS\ReturnObjects\Attendance;
use FredBradley\SOCS\ReturnObjects\Club;
use FredBradley\SOCS\ReturnObjects\Event;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Saloon\XmlWrangler\Exceptions\QueryAlreadyReadException;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Saloon\XmlWrangler\XmlReader;
use Throwable;

/**
 * Class CoCurricular
 */
final class CoCurricular extends SOCS
{
    /**
     * @return Collection<string, string|object>
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function getRegisters(?CarbonInterface $startDate = null): Collection
    {
        $startDate = $this->dateIfNull($startDate);

        $query = [
            'startdate' => $startDate->format(self::DATE_STRING),
        ];

        $options = $this->loadQuery(array_merge([
            'data' => 'registers',
        ], $query));

        $response = $this->getResponse('cocurricular.ashx', ['query' => $options]);

        if (! isset($response->pupil) || ! is_iterable($response->pupil)) {
            return collect();
        }

        return collect(collect($response)['pupil'])
            ->groupBy('eventid');
    }

    public function getEventById(int $eventId, ?CarbonInterface $date = null): ?Event
    {
        if (is_null($date)) {
            $dateWasNull = true;
        }
        $date = $this->dateIfNull($date);
        if (isset($dateWasNull)) {
            // TODO: this seems to time out since using XmlReader / XmlWrangler (dataset too big to iterate?)
            $events = $this->getEvents($date->toImmutable()->subYear(), $date->toImmutable()->addYear());
        } else {
            $events = $this->getEvents($date, $date);
        }

        return $events->where('eventid', $eventId)->first();
    }

    /**
     * @return Collection<string, Event>
     *
     * @throws GuzzleException
     */
    public function getEvents(CarbonInterface $startDate, CarbonInterface $endDate)
    {
        $query = [
            'startdate' => $startDate->format(self::DATE_STRING),
            'enddate' => $endDate->format(self::DATE_STRING),
            'staff' => true,
        ];

        $options = $this->loadQuery(array_merge([
            'data' => 'events',
        ], $query));

        $response = $this->getResponse('cocurricular.ashx', ['query' => $options]);

        return $this->getCollectionOfEventsFromResponse($response);
    }

    public function getClubById(int $clubId): ?Club
    {
        $clubs = $this->getClubs(true, true, true);

        return $clubs->where('clubId', $clubId)->first();
    }

    /**
     * @return Collection<array-key, Club>
     *
     * @throws GuzzleException
     */
    public function getClubs(
        bool $withPupils = false,
        bool $withStaff = false,
        bool $withPlanning = false
    ): Collection {
        $query = [];
        if ($withPlanning) {
            $query['planning'] = true;
        }
        if ($withPupils) {
            $query['pupils'] = true;
        }
        if ($withStaff) {
            $query['staff'] = true;
        }

        $options = $this->loadQuery(array_merge([
            'data' => 'clubs',
        ], $query));

        $results = $this->getResponse('cocurricular.ashx', ['query' => $options]);

        return $this->returnClubsFromResponse($results);
    }

    /**
     * @throws GuzzleException
     */
    public function getRegistrationDataForEvent(CarbonInterface $date, Event $event): Event
    {
        $date = $this->dateIfNull($date);

        $query = [
            'startdate' => $date->format(self::DATE_STRING),
        ];

        $options = $this->loadQuery(array_merge([
            'data' => 'registers',
        ], $query));

        $response = $this->getResponse('cocurricular.ashx', ['query' => $options]);

        return $this->addRegistrationDataToResponse($response, $event);
    }

    /**
     * @return Collection<array-key, Club>
     */
    private function returnClubsFromResponse(XmlReader $response): Collection
    {
        $results = $response->value('club')->collect();

        return $results->mapInto(Club::class);
    }

    private function dateIfNull(?CarbonInterface $date = null): CarbonInterface
    {
        if (is_null($date)) {
            $date = Carbon::today();
        }

        return $date;
    }

    /**
     * @return Collection<array-key, Event>
     */
    private function getCollectionOfEventsFromResponse(XmlReader $response): Collection
    {
        $results = $response->value('event')->collect();

        return $results->mapInto(Event::class)->map(function ($item) {
            $date = Carbon::createFromFormat('d/m/Y', $item->startdate);
            $registrationData = $this->getRegistrationDataForEvent($date, $item);
            $item->register = $registrationData->register->values();

            return $item;
        });
    }

    /**
     * @throws QueryAlreadyReadException
     * @throws XmlReaderException
     * @throws Throwable
     */
    private function addRegistrationDataToResponse(XmlReader $registrationResponse, Event $event): Event
    {
        $events = $registrationResponse->value('pupil')->collect();

        $event->register = $events->where('eventid', $event->eventid)->map(function ($item) {
            unset($item['eventid']);

            $item['attendance'] = Attendance::from($item->attendance ?? '');

            return $item;
        });

        return $event;
    }
}
