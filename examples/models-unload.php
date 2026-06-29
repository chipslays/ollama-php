<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$status = $ollama->models()->unload('ornith:9b-q4_K_M');

echo $status ? 'unloaded' : 'failed';
