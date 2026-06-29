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

## Examples 👀

There are many, many examples for each endpoint in the [examples](/examples) folder, come and explore!

## License

MIT
