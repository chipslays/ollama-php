<h1 align="center">Ollama PHP</h1>
<center>
A lightweight PHP client for the Ollama API.
</center>
<center>
🦙 + 🐘 = ❤️
</center>

---

## Installation

```bash
composer require ollama/php
```

## Usage

```php
use Ollama\Ollama;

$ollama = new Ollama;

$response = $ollama->chat()->send([
    'model' => 'gemma4:12b',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello!'],
    ],
]);

$response->dd();
```

## Examples

See the [examples](/examples) directory.

## License

MIT