<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$responses = $ollama->models()->createWithStream([
    'name' => 'batman',
    'modelfile' => "FROM ornith:9b-q4_K_M\nSYSTEM You are Batman from DC Comics, acting as an assistant.",
]);

foreach ($responses as $response) {
    echo $response->status;
}
