<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$responses = $ollama->models()->pullWithStream('gemma4:12b');

foreach ($responses as $response) {
    echo $response->status;
}