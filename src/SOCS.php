<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use SimpleXMLElement;

/**
 * Class SOCS
 */
abstract class SOCS
{
    /**
     * SOCS XML URIs like dates in this format!
     */
    protected const DATE_STRING = 'j M Y';

    protected string $baseUri = 'https://www.socscms.com/socs/xml/';

    protected Client $client;

    private int $socsId;

    private string $apiKey;

    /**
     * SOCS constructor.
     */
    public function __construct(Config $config)
    {
        $this->socsId = (int) $config->socsId;
        $this->apiKey = (string) $config->apiKey;
        $this->setClient();
    }

    /**
     * @return Collection<array-key, mixed>
     */
    public function recordsToCollection(string|SimpleXMLElement|false|null $records): Collection
    {
        $array = [];
        foreach ($records as $record) {
            $array[] = ((array) $record);
        }

        return collect($array);
    }

    protected function setClient(): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
        ]);
    }

    /**
     * @return array<int|mixed|string>
     *
     * @param array<string, mixed> $array
     */
    protected function loadQuery(array $array = []): array
    {
        $defaults = [
            'ID' => $this->socsId,
            'key' => $this->apiKey,
        ];

        return array_merge($defaults, $array);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @throws GuzzleException
     */
    protected function getResponse(string $uri, array $options = [], string $method = 'GET'): false|SimpleXMLElement|string|null
    {
        $response = $this->client->request($method, $uri, $options);

        return json_decode(json_encode(simplexml_load_string($response->getBody()->getContents())));
    }
}
