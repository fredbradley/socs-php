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
use Illuminate\Contracts\Support\Arrayable;
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

        if (! isset($response->pupil) || ! is_iterable($response->pupil)) {
            return collect();
        }

        return collect(collect($response)['pupil'])
            ->groupBy('eventid');
    }

    public function getEventById(int $eventId, ?CarbonInterface $date = null): ?Event
    {
        $date = $this->dateIfNull($date);
        $events = $this->getEvents($date->toImmutable()->subYear(), $date->toImmutable()->addYear());

        return $events->where('eventid', $eventId)->first();
    }

    /**
     * @return Collection<string, Event>
     *
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

    public function getClubById(int $clubId): ?Club
    {
        $clubs = $this->getClubs();

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
    private function returnClubsFromResponse(object $response): Collection
    {
        if (! isset($response->club)) {
            return collect();
        }

        $results = [];
        foreach ($response->club as $club) {
            $results[] = $club;
        }

        return collect($results)->mapInto(Club::class);
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
    private function getCollectionOfEventsFromResponse(object $response): Collection
    {
        if (! isset($response->event)) {
            return collect();
        }

        $results = [];
        foreach ($response->event as $event) {
            $results[] = $event;
        }

        return collect($results)->mapInto(Event::class)->map(function ($item) {
            $date = Carbon::createFromFormat('d/m/Y', $item->startdate);
            $registrationData = $this->getRegistrationDataForEvent($date, $item);
            $item->register = $registrationData->register->values();

            return $item;
        });
    }

    /**
     * @param  Arrayable<array-key,mixed>|iterable|null  $response
     */
    private function addRegistrationDataToResponse(Arrayable|\stdClass|iterable|null $response, Event $event): Event
    {
        $allEvents = collect($response);

        $event->register = collect($allEvents->first())->where('eventid', $event->eventid)->map(function ($item) {
            unset($item->eventid);
            if ($item->attendance instanceof \stdClass) {
                $item->attendance = Attendance::NOT_SET;
            } else {
                $item->attendance = Attendance::from($item->attendance);
            }

            return $item;
        });

        return $event;
    }
}
