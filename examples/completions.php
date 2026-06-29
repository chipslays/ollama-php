<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama();

$response = $ollama->completions()->generate([
    'model' => 'gemma4:12b',
    'prompt' => 'Why is the sky blue?',
]);

$response->dd();