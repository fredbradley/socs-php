<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use FredBradley\SOCS\ReturnObjects\Club;
use FredBradley\SOCS\ReturnObjects\Event;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

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

        if (! is_iterable($response->pupil)) {
            return collect();
        }

        return collect(collect($response)['pupil'])
            ->groupBy('eventid');
    }

    private function dateIfNull(?CarbonInterface $date = null): CarbonInterface
    {
        if (is_null($date)) {
            $date = Carbon::today();
        }

        return $date;
    }

    public function getEventById(int $eventId, ?CarbonInterface $date = null): ?Event
    {
        $date = $this->dateIfNull($date);
        $events = $this->getEvents($date->toImmutable()->subYear(), $date->toImmutable()->addYear());

        return $events->where('eventid', $eventId)->first();
    }

    /**
     * @throws GuzzleException
     */
    public function getEvents(CarbonInterface $startDate, CarbonInterface $endDate): Collection
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

    private function getCollectionOfEventsFromResponse(object $response): Collection
    {
        if (! isset($response->event)) {
            return collect();
        }

        $results = [];
        foreach ($response->event as $event) {
            $results[] = $event;
        }

        return collect($results)->mapInto(Event::class);
    }

    public function getClubById(int $clubId): ?Club
    {
        $clubs = $this->getClubs();

        return $clubs->where('clubId', $clubId)->first();
    }

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

        if (! isset($results->club)) {
            return collect();
        }

        return collect($results->club)->mapInto(Club::class);
    }

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

    private function addRegistrationDataToResponse(object $response, Event $event): Event
    {
        $allEvents = collect($response);

        $event->register = collect($allEvents->first())->where('eventid', $event->eventid);

        return $event;
    }
}
