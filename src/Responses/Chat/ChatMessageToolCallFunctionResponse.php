<?php

namespace Ollama\Responses\Chat;

use Ollama\Responses\AbstractResponse;

class ChatMessageToolCallFunctionResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly ?array $arguments = null,
        public readonly ?int $index = null,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            name: $attributes['name'],
            description: $attributes['description'] ?? null,
            arguments: $attributes['arguments'] ?? null,
            index: $attributes['index'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
            'arguments' => $this->arguments,
            'index' => $this->index,
        ], fn($value) => $value !== null);
    }
}
