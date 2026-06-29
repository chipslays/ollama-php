<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$completions = $ollama->completions()->stream([
    'model' => 'ornith:9b-q4_K_M',
    'prompt' => 'Why is the sky blue?',
]);

foreach ($completions as $completion) {
    echo $completion->response;
}
