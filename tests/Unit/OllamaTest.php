<?php

declare(strict_types=1);

use Ollama\Endpoints\Embed;
use Ollama\Endpoints\Blobs;
use Ollama\Endpoints\Chat;
use Ollama\Endpoints\Completions;
use Ollama\Endpoints\Models;
use Ollama\Http\Client;
use Ollama\Ollama;

it('has client', function () {
    $ollama = new Ollama();
    $client = $ollama->client();

    expect($client)->toBeInstanceOf(Client::class);
});

it('has blobs', function () {
    $ollama = new Ollama();

    expect($ollama->blobs())->toBeInstanceOf(Blobs::class);
});

it('has chat', function () {
    $ollama = new Ollama();

    expect($ollama->chat())->toBeInstanceOf(Chat::class);
});

it('has completions', function () {
    $ollama = new Ollama();

    expect($ollama->completions())->toBeInstanceOf(Completions::class);
});

it('has embed', function () {
    $ollama = new Ollama();

    expect($ollama->embed())->toBeInstanceOf(Embed::class);
});

it('has models', function () {
    $ollama = new Ollama();

    expect($ollama->models())->toBeInstanceOf(Models::class);
});

it('ollama is not running', function () {
   $ollama = new Ollama('http://localhost:12345/not_running');

   expect($ollama->isRunning())->toBeFalse();
});