<?php

namespace FredBradley\SOCS;

use Dotenv\Dotenv;

/**
 * Class Config
 * @package FredBradley\SOCS
 */
class Config
{
    /**
     * Config constructor.
     *
     * @param  string  $socsId
     * @param  string  $apiKey
     */
    public function __construct(string $socsId = 'SOCSID', string $apiKey = 'SOCSAPIKEY')
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__), ".env");
        $dotenv->safeLoad();
        $this->socsId = $_SERVER[$socsId] ?? $socsId;
        $this->apiKey = $_SERVER[$apiKey] ?? $apiKey;
    }
}
