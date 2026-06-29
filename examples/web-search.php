<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$results = $ollama->webSearch('php latest version', maxResults: 5);

foreach ($results as $item) {
    print_r($item);
}