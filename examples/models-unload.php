<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$status = $ollama->models()->unload('gemma4:12b');

echo $status ? 'unloaded' : 'failed';