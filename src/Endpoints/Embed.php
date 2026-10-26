<?php

namespace Ollama\Endpoints;

use Ollama\Http\Client;
use Ollama\Responses\EmbedResponse;

class Embed extends Endpoint
{
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
        $response = $this->client->post('api/embed', $parameters, stream: false);

        return EmbedResponse::from($response);
    }
}
