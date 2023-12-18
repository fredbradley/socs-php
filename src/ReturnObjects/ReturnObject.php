<?php

declare(strict_types=1);

namespace FredBradley\SOCS\ReturnObjects;

abstract class ReturnObject
{
    /**
     * @var array<string, mixed>
     */
    private array $attributes = [];

    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name];
    }
}
