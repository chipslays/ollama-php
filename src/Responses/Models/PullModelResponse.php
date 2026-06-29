<?php

namespace Ollama\Responses\Models;

use Ollama\Responses\AbstractResponse;

class PullModelResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $status,
        public readonly ?string $digest,
        public readonly ?int $total,
        public readonly ?int $completed,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            status: $attributes['status'],
            digest: $attributes['digest'] ?? null,
            total: $attributes['total'] ?? null,
            completed: $attributes['completed'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'digest' => $this->digest,
            'total' => $this->total,
            'completed' => $this->completed,
        ];
    }
}