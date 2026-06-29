<?php

namespace Ollama\Endpoints;

use Ollama\Http\Client;
use Throwable;

class Blobs
{
    public function __construct(protected Client $client) {
        //
    }

    public function exists(string $digest): bool
    {
        try {
            $response = $this->client->get('blobs/' . $digest, parseJson: false);

            return $response->getStatusCode() === 200;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function create(string $digest): bool
    {
        $response = $this->client->post('blobs/' . $digest, parseJson: false);

        return in_array($response->getStatusCode(), [200, 201], true);
    }
}