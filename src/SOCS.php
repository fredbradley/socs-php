<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Saloon\XmlWrangler\XmlReader;

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

    protected int $socsId;

    /**
     * SOCS constructor.
     */
    public function __construct(private Config $config)
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
        ]);
        $this->socsId = $config->socsId;
    }

    /**
     * @param  array<string, mixed>  $array
     * @return array<string,mixed>
     */
    protected function loadQuery(array $array = []): array
    {
        $defaults = [
            'ID' => $this->config->socsId,
            'key' => $this->config->apiKey,
        ];

        return array_merge($defaults, $array);
    }

    /**
     * @param  array<string, mixed>  $options
     *
     * @throws GuzzleException
     * @throws Exception
     */
    protected function getResponse(
        string $uri,
        array $options = [],
        string $method = 'GET'
    ): XmlReader {
        /**
         * FYI - I've benchmarked the difference between XMlReader::fromStream and
         * XmlReader::fromPsrResponse and the fromPsrResponse is faster.
         */
        $response = $this->client->request($method, $uri, $options);

        return XmlReader::fromPsrResponse($response);
    }
}
