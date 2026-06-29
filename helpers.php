<?php

use Ollama\Http\Client;
use Ollama\Ollama;

if (!function_exists('ollama')) {
    function ollama(
        string $url = Client::DEFAULT_URL,
        ?string $apiKey = null,
        array $options = []
    ): Ollama {
        return new Ollama($url, $apiKey, $options);
    }
}