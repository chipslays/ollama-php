<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$responses = $ollama->chat()->stream([
    'model' => 'gemma4:12b',
    'messages' => [
        ['role' => 'system', 'content' => 'You are a Ollama assistant.'],
        ['role' => 'user', 'content' => 'Hello!'],
        ['role' => 'assistant', 'content' => 'Hi! How can I help you today?'],
        ['role' => 'user', 'content' => 'I need help improving my PHP skills.'],
    ],
]);

foreach ($responses as $response) {
    echo $response->message->content;
}