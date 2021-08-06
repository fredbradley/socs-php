<?php

namespace FredBradley\SOCS;

use Dotenv\Dotenv;
use GuzzleHttp\Client;

class SOCS
{
    /**
     * @var int
     */
    public $socsId;
    /**
     * @var string
     */
    public $apiKey;

    /**
     * @var Client
     */
    private $client;
    public function __construct(string $socsId='SOCSID', string $apiKey='SOCSAPIKEY')
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__), ".env");
        $dotenv->load();
        $this->socsId = (int) $_ENV[$socsId];
        $this->apiKey = (string) $_ENV[$apiKey];
        $this->setClient();
    }

    /**
     * @return void
     */
    private function setClient(): void {
        $this->client = new Client([
            'base_uri' => 'https://www.socscms.com/socs/xml/',
        ]);
    }

    /**
     * @return \SimpleXMLElement|false
     */
    public function getClubs() {
        $response = $this->client->request("GET", "cocurricular.ashx", [
            'query' => [
                'data' => 'clubs',
                'pupils' => 1,
                'staff' => 1,
                'planning' => 1,
                'ID' => $this->socsId,
                'key' => $this->apiKey,
            ],
        ]);
        $xml = simplexml_load_string($response->getBody()->getContents());
        return $xml;
    }


    public function echoPhrase(string $str): string
    {
        return $str;
    }

}
