<?php

namespace Ollama\Responses\Chat;

use Ollama\Responses\AbstractResponse;

class ChatMessageResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $role,
        public readonly string $content,
        public readonly ?string $thinking = null,
        public readonly array $toolCalls = [],
        public readonly array $images = [],
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            role: $attributes['role'],
            content: $attributes['content'],
            thinking: $attributes['thinking'] ?? null,
            toolCalls: array_map(
                fn(array $toolCall) => ChatMessageToolCallResponse::from($toolCall),
                $attributes['tool_calls'] ?? [],
            ),
            images: $attributes['images'] ?? [],
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'role' => $this->role,
            'content' => $this->content,
            'thinking' => $this->thinking,
            'tool_calls' => array_map(
                static fn(ChatMessageToolCallResponse $response): array => $response->toArray(),
                $this->toolCalls,
            ),
            'images' => $this->images,
        ], fn($value) => $value !== null && !(is_array($value) && empty($value)));
    }
}