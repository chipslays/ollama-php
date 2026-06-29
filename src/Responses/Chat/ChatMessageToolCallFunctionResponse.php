<?php

namespace Ollama\Responses\Chat;

use Ollama\Responses\AbstractResponse;

class ChatMessageToolCallFunctionResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $name,
        public readonly ?string $description = null,
        public readonly ?array $arguments = null,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            name: $attributes['function']['name'],
            description: $attributes['function']['description'] ?? null,
            arguments: $attributes['function']['arguments'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'function' => array_filter([
                'name' => $this->name,
                'description' => $this->description,
                'arguments' => $this->arguments,
            ]),
        ], fn($value) => !empty($value));
    }
}