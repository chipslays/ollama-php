<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$responses = $ollama->models()->pushWithStream('ornith:9b-q4_K_M-copy');

foreach ($responses as $response) {
    echo $response->status;
}
