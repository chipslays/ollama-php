<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$response = $ollama->embed()->create([
    'model' => 'qwen3-embedding:0.6b',
    'input' => [
        'Why is the sky blue?',
    ],
]);

$response->dd();