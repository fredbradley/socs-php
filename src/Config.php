<?php

namespace FredBradley\SOCS;

use Dotenv\Dotenv;

/**
 * Class Config
 * @package FredBradley\SOCS
 */
class Config
{
    public int $socsId;
    public string $apiKey;

    /**
     * Config constructor.
     *
     * @param  string  $socsId
     * @param  string  $apiKey
     */
    public function __construct(string $socsId = 'SOCSID', string $apiKey = 'SOCSAPIKEY')
    {
        $filename = dirname(__DIR__).'/.env';
        if (file_exists($filename)) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__), ".env");
            $dotenv->safeLoad();
        }
        $this->socsId = $_SERVER[$socsId] ?? $socsId;
        $this->apiKey = $_SERVER[$apiKey] ?? $apiKey;
    }
}
