<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$responses = $ollama->models()->pullWithStream('ornith:9b-q4_K_M');

foreach ($responses as $response) {
    echo $response->status;
}
