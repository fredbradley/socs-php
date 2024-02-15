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

    public const SPORTCOACHING = 'sportcoaching';

    public const ACADEMIC = 'academictutoring';

    public const PERFORMINGARTS = 'performingarts';

    /**
     * @var array<string>
     */
    private array $dataFeeds = [
        self::MUSIC,
        self::SPORTCOACHING,
        self::ACADEMIC,
        self::PERFORMINGARTS,
    ];

    public function __call(string $name, array $arguments)
    {
        $method = match ($name) {
            'getMusicLessons' => self::MUSIC,
            'getSportCoaching' => self::SPORTCOACHING,
            'getAcademicTutoring' => self::ACADEMIC,
            'getPerformingArts' => self::PERFORMINGARTS,
            default => throw new Exception('Method not allowed'),
        };
        if (in_array($method, $this->dataFeeds)) {
            return $this->getFeed($method, $arguments[0] ?? null);
        }
    }

    /**
     * @return Collection<array-key, MusicLesson>
     *
     * @throws GuzzleException
     */
    private function getFeed(string $feed, ?CarbonInterface $startDate = null): Collection
    {
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
        $lessons = $response->value('lesson')->collect();

        return $lessons
            ->mapInto(MusicLesson::class);
    }
}
