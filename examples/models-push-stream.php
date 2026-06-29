<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$responses = $ollama->models()->pushWithStream('gemma4:12b-copy');

foreach ($responses as $response) {
    echo $response->status;
}