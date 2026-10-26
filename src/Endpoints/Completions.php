<?php

namespace Ollama\Endpoints;

use Ollama\Http\Client;
use Ollama\Responses\CompletionResponse;
use Ollama\Responses\StreamResponse;

class Completions extends Endpoint
{
    /**
     * Generates a response for the provided prompt.
     *
     * @see https://docs.ollama.com/api/generate
     *
     * @param array $parameters
     * @return CompletionResponse
     */
    public function generate(array $parameters): CompletionResponse
    {
        $response = $this->client->post('api/generate', $parameters, stream: false);

        return CompletionResponse::from($response);
    }

    /**
     * @see https://docs.ollama.com/api/generate
     *
     * @param array $parameters
     * @return StreamResponse
     */
    public function stream(array $parameters): StreamResponse
    {
        $response = $this->client->post('api/generate', $parameters, stream: true);

        return new StreamResponse(CompletionResponse::class, $response);
    }
}
