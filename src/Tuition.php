<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use FredBradley\SOCS\ReturnObjects\MusicLesson;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

/**
 * Class Tuition
 */
final class Tuition extends SOCS
{
    public const MUSIC = 'musiclessons';

    public const SPORTSCOACHING = 'sportscoaching';

    public const ACADEMIC = 'academictutoring';

    public const PERFORMINGARTS = 'performingarts';

    /**
     * @var array<string>
     */
    private array $dataFeeds = [
        self::MUSIC,
        self::SPORTSCOACHING,
        self::ACADEMIC,
        self::PERFORMINGARTS,
    ];

    /**
     * @throws Exception
     */
    public function getMusicLessons(?CarbonInterface $startDate = null): false|\SimpleXMLElement|string|null
    {
        return $this->getFeed(self::MUSIC, $startDate);
    }

    /**
     * @throws Exception
     */
    public function getAcademicTutoring(?CarbonInterface $startDate = null): false|\SimpleXMLElement|string|null
    {
        return $this->getFeed(self::ACADEMIC, $startDate);
    }

    /**
     * @throws Exception
     */
    public function getPerformingArts(
        ?CarbonInterface $startDate = null
    ): false|\SimpleXMLElement|string|null {
        return $this->getFeed(self::PERFORMINGARTS, $startDate);
    }

    /**
     * @throws Exception
     */
    public function getSportsCoaching(?CarbonInterface $startDate = null): false|\SimpleXMLElement|string|null
    {
        return $this->getFeed(self::SPORTSCOACHING, $startDate);
    }

    /**
     * @return Collection<array-key, MusicLesson>
     *
     * @throws GuzzleException
     */
    private function getFeed(string $feed, ?CarbonInterface $startDate = null): Collection
    {
        if (! in_array($feed, $this->dataFeeds)) {
            throw new Exception('Unexpected data feed requested');
        }

        if (is_null($startDate)) {
            $startDate = Carbon::today();
        }

        $query = [
            'startdate' => $startDate->format(self::DATE_STRING),
            'enddate' => $startDate->addDays(7)->format(self::DATE_STRING),
        ];

        $options = $this->loadQuery(array_merge([
            'data' => $feed,
        ], $query));

        $response = $this->getResponse('tuition.ashx', ['query' => $options]);

        return $this->recordsToCollection($response)
            ->mapInto(MusicLesson::class);
    }
}
