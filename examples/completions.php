<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama();

$response = $ollama->completions()->generate([
    'model' => 'ornith:9b-q4_K_M',
    'prompt' => 'Why is the sky blue?',
    'thinking' => false,
]);

$response->dd();
