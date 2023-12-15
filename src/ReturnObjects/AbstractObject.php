<?php

namespace FredBradley\SOCS\ReturnObjects;

class AbstractObject
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
