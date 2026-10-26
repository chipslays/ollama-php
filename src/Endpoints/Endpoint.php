<?php

namespace Ollama\Endpoints;

use Ollama\Http\Client;

abstract class Endpoint
{
    public function __construct(protected Client $client)
    {
        //
    }
}
