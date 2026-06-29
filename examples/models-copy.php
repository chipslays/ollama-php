<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$status = $ollama->models()->copy('gemma4:12b', 'gemma4:12b-copy');

echo $status ? 'copied' : 'failed';