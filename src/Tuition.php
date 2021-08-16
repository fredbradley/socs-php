<?php

namespace FredBradley\SOCS;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use FredBradley\SOCS\ReturnObjects\MusicLesson;

/**
 * Class Tuition
 * @package FredBradley\SOCS
 */
class Tuition extends SOCS
{
    /**
     *
     */
    public const MUSIC = "musiclessons";
    /**
     *
     */
    public const SPORTSCOACHING = "sportscoaching";
    /**
     *
     */
    public const ACADEMIC = "academictutoring";
    /**
     *
     */
    public const PERFORMINGARTS = "performingarts";

    /**
     * @var array
     */
    private $dataFeeds = [
        self::MUSIC,
        self::SPORTSCOACHING,
        self::ACADEMIC,
        self::PERFORMINGARTS,
    ];

    /**
     * @param  \Carbon\CarbonInterface|null  $startDate
     *
     * @return false|\SimpleXMLElement|string|null
     * @throws \Exception
     */
    public function getMusicLessons(CarbonInterface $startDate = null)
    {
        return $this->getFeed(self::MUSIC, $startDate);
    }

    /**
     * @param  string  $feed
     * @param  \Carbon\CarbonInterface|null  $startDate
     *
     * @return false|\SimpleXMLElement|string|null
     * @throws \Exception
     */
    private function getFeed(string $feed, CarbonInterface $startDate = null)
    {
        if (! in_array($feed, $this->dataFeeds)) {
            throw new Exception("Unexpected data feed requested");
        }

        if (is_null($startDate)) {
            $startDate = Carbon::today();
        }

        $query = [
            'startdate' => $startDate->format(self::DATE_STRING),
        ];

        $options = $this->loadQuery(array_merge([
            'data' => $feed,
        ], $query));

        $response = $this->getResponse('tuition.ashx', ['query' => $options]);

        return $this->recordsToCollection($response)->mapInto(MusicLesson::class);
    }

    /**
     * @param  \Carbon\CarbonInterface|null  $startDate
     *
     * @return false|\SimpleXMLElement|string|null
     * @throws \Exception
     */
    public function getAcademicTutoring(CarbonInterface $startDate = null)
    {
        return $this->getFeed(self::ACADEMIC, $startDate);
    }

    /**
     * @param  \Carbon\CarbonInterface|null  $startDate
     *
     * @return false|\SimpleXMLElement|string|null
     * @throws \Exception
     */
    public function getPerformingArts(CarbonInterface $startDate = null)
    {
        return $this->getFeed(self::PERFORMINGARTS, $startDate);
    }

    /**
     * @param  \Carbon\CarbonInterface|null  $startDate
     *
     * @return false|\SimpleXMLElement|string|null
     * @throws \Exception
     */
    public function getSportsCoaching(CarbonInterface $startDate = null)
    {
        return $this->getFeed(self::SPORTSCOACHING, $startDate);
    }
}
