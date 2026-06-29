<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$response = $ollama->models()->create([
    'name' => 'batman',
    'modelfile' => "FROM gemma4:12b\nSYSTEM You are Batman from DC Comics, acting as an assistant.",
]);

$response->dd();