<?php

namespace Ollama\Endpoints;

use Ollama\Http\Client;
use Ollama\Responses\CompletionResponse;
use Ollama\Responses\StreamResponse;

class Completions
{
    /**
     * @param Client $client
     */
    public function __construct(protected Client $client)
    {
        //
    }

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
        $response = $this->client->post('generate', $parameters, false);

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
        $response = $this->client->post('generate', $parameters, true);

        return new StreamResponse(CompletionResponse::class, $response);
    }
}
