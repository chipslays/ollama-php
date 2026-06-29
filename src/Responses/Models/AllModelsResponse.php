<?php

namespace Ollama\Responses\Models;

use Ollama\Responses\AbstractResponse;

class AllModelsResponse extends AbstractResponse
{
    protected function __construct(
        public readonly array $models,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            models: array_map(
                fn(array $model) => AllModelsModelResponse::from($model),
                $attributes['models'] ?? [],
            ),
        );
    }

    public function toArray(): array
    {
        return [
            'models' => array_map(
                static fn(AllModelsModelResponse $model) => $model->toArray(),
                $this->models,
            ),
        ];
    }
}