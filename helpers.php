<?php

use Ollama\Http\Client;
use Ollama\Ollama;

if (!function_exists('ollama')) {
    function ollama(
        string $url = Client::OLLAMA_HOST_BY_DEFAULT,
        ?string $apiKey = null,
        array $httpOptions = []
    ): Ollama {
        return new Ollama($url, $apiKey, $httpOptions);
    }
}
