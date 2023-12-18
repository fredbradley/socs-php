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
        $this->socsId = $config->socsId;
        $this->apiKey = $config->apiKey;

        $this->client = new Client([
            'base_uri' => $this->baseUri,
        ]);
    }

    /**
     * @param  array<string, mixed>  $array
     * @return array<int|mixed|string>
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
     * @param  array<string, mixed>  $options
     *
     * @throws GuzzleException
     */
    protected function getResponse(
        string $uri,
        array $options = [],
        string $method = 'GET'
    ): false|SimpleXMLElement|string|null|\stdClass {
        $response = $this->client->request($method, $uri, $options);

        return json_decode(json_encode(simplexml_load_string($response->getBody()->getContents())));
    }
}
