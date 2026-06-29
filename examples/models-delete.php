<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$status = $ollama->models()->delete('batman');

echo $status ? 'deleted' : 'failed';