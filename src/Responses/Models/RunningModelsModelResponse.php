<?php

namespace Ollama\Responses\Models;

use Ollama\Responses\AbstractResponse;

class RunningModelsModelResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $name,
        public readonly string $model,
        public readonly int $size,
        public readonly string $digest,
        public readonly AllModelsModelDetailsResponse $details,
        public readonly string $expiresAt,
        public readonly int $sizeVram,
        public readonly ?int $contextLength = null,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            name: $attributes['name'] ?? '',
            model: $attributes['model'] ?? '',
            size: $attributes['size'] ?? 0,
            digest: $attributes['digest'] ?? '',
            details: isset($attributes['details'])
                ? AllModelsModelDetailsResponse::from($attributes['details'])
                : AllModelsModelDetailsResponse::from([]),
            expiresAt: $attributes['expires_at'] ?? '',
            sizeVram: $attributes['size_vram'] ?? 0,
            contextLength: $attributes['context_length'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'model' => $this->model,
            'size' => $this->size,
            'digest' => $this->digest,
            'details' => $this->details->toArray(),
            'expires_at' => $this->expiresAt,
            'size_vram' => $this->sizeVram,
            'context_length' => $this->contextLength,
        ], fn($value) => $value !== null && !(is_array($value) && empty($value)));
    }
}