<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$status = $ollama->models()->copy('ornith:9b-q4_K_M', 'ornith:9b-q4_K_M-copy');

echo $status ? 'copied' : 'failed';
