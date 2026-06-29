<?php

namespace Ollama\Responses\Models;

use Ollama\Responses\AbstractResponse;

class PushModelResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $status,
        public readonly ?string $digest,
        public readonly ?int $total,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            status: $attributes['status'],
            digest: $attributes['digest'] ?? null,
            total: $attributes['total'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'digest' => $this->digest,
            'total' => $this->total,
        ];
    }
}