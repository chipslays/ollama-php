<?php

namespace Ollama\Endpoints;

use Ollama\Http\Client;
use Ollama\Responses\SystemOne\SystemOneResponse;

class SystemOne extends Endpoint
{
    /**
     * Ask choice, yes/no, and scoring questions about a shared state
     * using a System One model.
     *
     * @see https://docs.ollama.com/capabilities/decision
     *
     * @param array $parameters
     * @return SystemOneResponse
     */
    public function ask(array $parameters): SystemOneResponse
    {
        $response = $this->client->post('v1/systemone', $parameters);

        return SystemOneResponse::from($response);
    }
}
