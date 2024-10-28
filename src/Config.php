<?php

declare(strict_types=1);

namespace FredBradley\SOCS;

/**
 * Class Config
 */
final class Config
{
    public function __construct(public int $socsId, public string $apiKey) {}
}
