<?php

namespace FredBradley\SOCS;

use GuzzleHttp\Client;

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

    protected function setClient(): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
        ]);
    }

    protected function loadQuery(array $array = []): array
    {
        $defaults = [
            'ID' => $this->socsId,
            'key' => $this->apiKey,
        ];

        return array_merge($defaults, $array);
    }

    /**
     * @return false|\SimpleXMLElement|string|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getResponse(string $uri, array $options = [], string $method = 'GET')
    {
        $response = $this->client->request($method, $uri, $options);

        return simplexml_load_string($response->getBody()->getContents());
    }

    public function recordsToCollection($records): \Illuminate\Support\Collection
    {
        $array = [];
        foreach ($records as $record) {
            $array[] = ((array) $record);
        }

        return collect($array);
    }
}
