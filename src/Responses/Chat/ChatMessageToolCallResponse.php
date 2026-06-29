<?php

namespace Ollama\Responses\Chat;

use Ollama\Responses\AbstractResponse;

class ChatMessageToolCallResponse extends AbstractResponse
{
    protected function __construct(
        public readonly ChatMessageToolCallFunctionResponse $function,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            function: ChatMessageToolCallFunctionResponse::from($attributes['function']),
        );
    }

    public function toArray(): array
    {
        return [
            'function' => $this->function->toArray(),
        ];
    }
}