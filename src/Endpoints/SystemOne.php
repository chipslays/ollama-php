<?php

namespace Ollama\Endpoints;

use Ollama\Requests\SystemOne\Questions;
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
    public function evaluate(array $parameters): SystemOneResponse
    {
        if (array_key_exists('questions', $parameters) && $parameters['questions'] instanceof Questions) {
            $parameters['questions'] = $parameters['questions']->toArray();
        }

        $response = $this->client->post('v1/systemone', $parameters);

        return SystemOneResponse::from($response);
    }
}
