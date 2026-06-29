<?php

namespace Ollama\Responses;

abstract class AbstractResponse
{
    abstract public function toArray(): array;

    public function dd(): never
    {
        if (function_exists('dd')) {
            dd($this->toArray());
        } else {
            var_dump($this->toArray());
            exit(1);
        }
    }
}