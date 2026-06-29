<?php

namespace Ollama\Endpoints;

use Ollama\Http\Client;
use Ollama\Responses\EmbedResponse;

class Embed
{
    /**
     * @param Client $client
     */
    public function __construct(protected Client $client)
    {
        //
    }

    /**
     * Creates vector embeddings representing the input text.
     *
     * @see https://docs.ollama.com/api/embed
     *
     * @param array $parameters
     * @return EmbedResponse
     */
    public function create(array $parameters): EmbedResponse
    {
        $response = $this->client->post('embed', $parameters, false);

        return EmbedResponse::from($response);
    }
}