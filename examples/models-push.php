<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$response = $ollama->models()->push('ornith:9b-q4_K_M-copy');

$response->dd();
