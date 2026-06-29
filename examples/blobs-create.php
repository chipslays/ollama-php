<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$status = $ollama->blobs()->create('blobname');

echo $status ? 'created' : 'failed';