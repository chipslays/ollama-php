<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$response = $ollama->models()->show('gemma4:12b');

$response->dd();