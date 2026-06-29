<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$status = $ollama->blobs()->exists('blobname');

echo $status ? 'exists' : 'does not exist';