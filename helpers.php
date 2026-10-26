<?php

use Ollama\Http\Client;
use Ollama\Ollama;
use Ollama\Requests\SystemOne\Questions;

if (!function_exists('ollama')) {
    /**
     *
     * @param string $host
     * @param string|null $apiKey
     * @param array $httpOptions
     * @return Ollama
     */
    function ollama(
        string $host = Client::OLLAMA_HOST_BY_DEFAULT,
        ?string $apiKey = null,
        array $httpOptions = []
    ): Ollama {
        return new Ollama($host, $apiKey, $httpOptions);
    }
}

if (!function_exists('questions')) {
    /**
     * A named collection of questions for the System One API.
     *
     * @return Questions
     */
    function questions(): Questions {
        return Questions::make();
    }
}
