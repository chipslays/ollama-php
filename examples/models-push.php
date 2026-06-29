<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$response = $ollama->models()->push('gemma4:12b-copy');

$response->dd();