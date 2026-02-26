<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use FredBradley\SOCS\ReturnObjects\Tuition as TuitionReturnObject;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Class Tuition
 * @method getMusicLessons()
 * @method getSportCoaching()
 * @method getAcademicTutoring()
 * @method getPerformingArts()
 */
final class Tuition extends SOCS
{
    public const string MUSIC = 'musiclessons';

    public const string SPORTCOACHING = 'sportcoaching';

    public const string ACADEMIC = 'academictutoring';

    public const string PERFORMINGARTS = 'performingarts';

    /**
     * @var array<string>
     */
    private array $dataFeeds = [
        self::MUSIC,
        self::SPORTCOACHING,
        self::ACADEMIC,
        self::PERFORMINGARTS,
    ];

    /**
     * @var array<string, string>
     */
    private array $methodMap = [
        'getMusicLessons' => self::MUSIC,
        'getSportCoaching' => self::SPORTCOACHING,
        'getAcademicTutoring' => self::ACADEMIC,
        'getPerformingArts' => self::PERFORMINGARTS,
    ];

    /**
     * @param  array<array-key, mixed>  $arguments
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function __call(string $name, array $arguments): Collection
    {
        if (! array_key_exists($name, $this->methodMap)) {
            throw new Exception('Method not allowed');
        }

        $method = $this->methodMap[$name];

        return $this->getFeed($method, $arguments[0] ?? null);
    }

    /**
     * @throws Exception
     * @throws GuzzleException|Throwable
     *
     * @return Collection<array-key, array>
     */
    public function getRelationships(string $type): Collection
    {
        $method = match ($type) {
            'musiclessons' => 'musicstaffpupils',
            'sportcoaching' => 'sportcoachingstaffpupils',
            'academictutoring' => 'academictutoringstaffpupils',
            'performingarts' => 'performingartsstaffpupils',
            default => throw new Exception('Method not allowed'),
        };

        $options = $this->loadQuery([
            'data' => $method,
        ]);

        $response = $this->getResponse('tuition.ashx', ['query' => $options]);

        $result = $response->value('staffpupil')->collect();

        $validResult = $result->every(function (array $item) {
            /**
             * Collection Validation: The every method is used to iterate over each element
             * in the collection. The callback function returns true if pupilId and
             * staffId are both populated; otherwise, it returns false.
             */
            return ! empty($item['staffid']) && ! empty($item['pupilid']);
        });

        if (! $validResult) {
            error_log('Invalid data returned from SOCS. Please check the data feed: '.$method.' for SOCSID: '.$this->socsId);
        }

        return $result;
    }

    /**
     * @return Collection<array-key, Tuition>
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
            ->mapInto(TuitionReturnObject::class);
    }
}
