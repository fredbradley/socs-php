<?php

namespace FredBradley\SOCS;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

/**
 * Class CoCurricular
 */
class CoCurricular extends SOCS
{
    public function getClubs(bool $withPupils = false, bool $withStaff = false, bool $withPlanning = false): Collection
    {
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

        return $this->recordsToCollection($this->getResponse('cocurricular.ashx', ['query' => $options]));
    }

    /**
     * @return false|\SimpleXMLElement|string|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getEvents(CarbonInterface $startDate, CarbonInterface $endDate, bool $staff = false)
    {
        $query = [
            'startdate' => $startDate->format(self::DATE_STRING),
            'enddate' => $endDate->format(self::DATE_STRING),
        ];

        if ($staff) {
            $query['staff'] = true;
        }

        $options = $this->loadQuery(array_merge([
            'data' => 'events',
        ], $query));

        return $this->getResponse('cocurricular.ashx', ['query' => $options]);
    }

    /**
     * @return false|\SimpleXMLElement|string|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRegisters(CarbonInterface $startDate = null)
    {
        if (is_null($startDate)) {
            $startDate = Carbon::today();
        }

        $query = [
            'startdate' => $startDate->format(self::DATE_STRING),
        ];

        $options = $this->loadQuery(array_merge([
            'data' => 'registers',
        ], $query));

        return $this->getResponse('cocurricular.ashx', ['query' => $options]);
    }
}
