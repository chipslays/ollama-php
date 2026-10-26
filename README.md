<h1 align="center">Ollama PHP</h1>

<p align="center">
A lightweight PHP client for the Ollama API.
</p>

<p align="center">
🦙 + 🐘 = ❤️
</p>

---

## Installation

```bash
composer require ollama/php --prefer-dist
```

## Quick Start

Get up and running with the Ollama PHP client in two lines of code. The client ships with two entry points: a fluent `Ollama` facade that returns typed response objects, and a low-level `Client` that gives you direct access to the HTTP API.

### Using the `Ollama` facade
The `Ollama` class is the recommended starting point. It exposes every API endpoint as a chainable method (`chat()`, `completions()`, `embed()`, `models()`, `blobs()`, `systemOne()`) and hydrates every response into a dedicated DTO, so you get IDE autocompletion, proper types, and no array-key guessing.

```php
use Ollama\Ollama;

$ollama = new Ollama;

$response = $ollama->chat()->send([
    'model' => 'gemma4:12b',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello world!'],
    ],
]);

echo $response->message->content;
```

Here `$response` is a `ChatResponse` instance - `$response->message->content` is a typed `string`, not `$response['message']['content']`. Use this when you want the ergonomics of an SDK.

### Using the `Client` directly

If you prefer to talk to Ollama's HTTP API yourself - for example, to hit an endpoint not yet wrapped by the SDK, or to inspect the raw JSON - instantiate `Client` and call `post()` / `get()` / `delete()` yourself. Requests and responses are plain arrays.

```php
use Ollama\Client;

$client = new Client;

$result = $client->post('api/chat', [
    'model' => 'gemma4:12b',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello world!'],
    ],
]);

var_dump($result);
```

This is the same request, but you get the raw associative array back - no DTO, no magic. Use this when you want full control over the payload or need to reach an endpoint the SDK doesn't yet cover.

## Configuring the client

By default both entry points talk to http://127.0.0.1:11434. To point at a different host (remote server, Ollama Cloud, a proxy), pass options to the constructor:

```php
$ollama = new Ollama(
    host: 'https://ollama.com',
    apiKey: getenv('OLLAMA_API_KEY'),
    httpOptions: [] // GuzzleHttp\Client
);
```

## Streaming

Both `Completions` and `Chat` expose a `stream()` method that returns a `StreamResponse` you can iterate over to read chunks as they arrive:

```php
foreach ($ollama->chat()->stream([/* ... */]) as $chunk) {
    echo $chunk->message->content;
}
```

## Examples 👀

Every endpoint has a runnable example in the [`examples`](/examples) directory - from basic chat and completions to streaming, embeddings, and model management. Browse the folder to see each one in context.

## License

MIT
