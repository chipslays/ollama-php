<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$response = $ollama->chat()->send([
    'model' => 'ornith:9b-q4_K_M',
    'messages' => [
        ['role' => 'system', 'content' => 'You are a Ollama assistant.'],
        ['role' => 'user', 'content' => 'Hello!'],
        ['role' => 'assistant', 'content' => 'Hi! How can I help you today?'],
        ['role' => 'user', 'content' => 'I need help improving my PHP skills.'],
    ],
]);

$response->dd();
