<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$completions = $ollama->completions()->stream([
    'model' => 'gemma4:12b',
    'prompt' => 'Hello, how are you?',
]);

foreach ($completions as $completion) {
    echo $completion->response;
}