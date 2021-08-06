<?php

namespace FredBradley\SOCS;

class SOCS
{
    public $socsId;
    public $apiKey;

    public function __construct(int $socsId, string $apiKey)
    {
        $this->socsId = $socsId;
        $this->apiKey = $apiKey;
    }

    public function echoPhrase(string $str): string
    {
        return $str;
    }
}
