<?php

namespace Ollama\Responses\Models;

use Ollama\Responses\AbstractResponse;

class AllModelsModelDetailsResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $format,
        public readonly string $family,
        public readonly string $parameterSize,
        public readonly string $quantizationLevel,
        public readonly array $families = [],
        public readonly string $parentModel = '',
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            format: $attributes['format'] ?? '',
            family: $attributes['family'] ?? '',
            parameterSize: $attributes['parameter_size'] ?? '',
            quantizationLevel: $attributes['quantization_level'] ?? '',
            families: $attributes['families'] ?? [],
            parentModel: $attributes['parent_model'] ?? '',
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'format' => $this->format,
            'family' => $this->family,
            'parameter_size' => $this->parameterSize,
            'quantization_level' => $this->quantizationLevel,
            'families' => $this->families,
            'parent_model' => $this->parentModel,
        ], fn($value) => $value !== null && !(is_array($value) && empty($value)));
    }
}