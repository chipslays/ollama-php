<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$result = $ollama->isRunning();

echo $result ? 'running' : 'not running';