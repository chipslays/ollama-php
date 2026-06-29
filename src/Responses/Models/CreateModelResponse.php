<?php

namespace Ollama\Responses\Models;

use Ollama\Responses\AbstractResponse;

class CreateModelResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $status,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            status: $attributes['status'],
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}