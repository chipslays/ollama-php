<?php

namespace Ollama\Endpoints;

use Ollama\Http\Client;
use Ollama\Responses\Chat\ChatResponse;
use Ollama\Responses\StreamResponse;

class Chat
{
    public function __construct(protected Client $client)
    {
        //
    }

    /**
     * Generate the next chat message in a conversation between a user and an assistant.
     *
     * @see https://docs.ollama.com/api/chat
     *
     * @param array $parameters
     * @return ChatResponse
     */
    public function send(array $parameters): ChatResponse
    {
        $response = $this->client->post('chat', $parameters, false);

        return ChatResponse::from($response);
    }

    /**
     * @see https://docs.ollama.com/api/chat
     *
     * @param array $parameters
     * @return StreamResponse
     */
    public function stream(array $parameters): StreamResponse
    {
        $response = $this->client->post('chat', $parameters, true);

        return new StreamResponse(ChatResponse::class, $response);
    }
}