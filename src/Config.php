<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

use Dotenv\Dotenv;

/**
 * Class Config
 */
final class Config
{
    /**
     * Config constructor.
     */
    public function __construct(public int $socsId = 0, public string $apiKey = 'SOCSAPIKEY')
    {
        $filename = dirname(__DIR__).'/.env';
        if (file_exists($filename)) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__), '.env');
            $dotenv->safeLoad();
        }
        // Weird little hack because .env things everything is a string
        $socsUserId = is_null($_SERVER['SOCSID']) ? null : (int) $_SERVER['SOCSID'];

        $this->socsId = $socsUserId ?? $socsId;
        $this->apiKey = $_SERVER['SOCSAPIKEY'] ?? $apiKey;
    }
}
