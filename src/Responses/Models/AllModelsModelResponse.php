<?php

namespace Ollama\Responses\Models;

use Ollama\Responses\AbstractResponse;

class AllModelsModelResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $name,
        public readonly string $modifiedAt,
        public readonly int $size,
        public readonly string $digest,
        public readonly AllModelsModelDetailsResponse $details,
        public readonly ?string $model = null,
        public readonly ?string $remoteModel = null,
        public readonly ?string $remoteHost = null,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            name: $attributes['name'] ?? '',
            modifiedAt: $attributes['modified_at'] ?? '',
            size: $attributes['size'] ?? 0,
            digest: $attributes['digest'] ?? '',
            details: isset($attributes['details'])
                ? AllModelsModelDetailsResponse::from($attributes['details'])
                : AllModelsModelDetailsResponse::from([]),
            model: $attributes['model'] ?? null,
            remoteModel: $attributes['remote_model'] ?? null,
            remoteHost: $attributes['remote_host'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'model' => $this->model,
            'remote_model' => $this->remoteModel,
            'remote_host' => $this->remoteHost,
            'modified_at' => $this->modifiedAt,
            'size' => $this->size,
            'digest' => $this->digest,
            'details' => $this->details->toArray(),
        ], fn($value) => $value !== null && !(is_array($value) && empty($value)));
    }
}