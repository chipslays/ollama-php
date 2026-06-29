<?php

namespace Ollama\Responses\Models;

use Ollama\Responses\AbstractResponse;

class ShowModelResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $modelfile,
        public readonly string $parameters,
        public readonly string $template,
        public readonly AllModelsModelDetailsResponse $details,
        public readonly array $modelInfo = [],
        public readonly ?string $license = null,
        public readonly ?string $system = null,
        public readonly ?array $messages = null,
    ) {
        //
    }

    public static function from(array $attributes): ShowModelResponse
    {
        return new self(
            modelfile: $attributes['modelfile'] ?? '',
            parameters: $attributes['parameters'] ?? '',
            template: $attributes['template'] ?? '',
            details: isset($attributes['details'])
                ? AllModelsModelDetailsResponse::from($attributes['details'])
                : AllModelsModelDetailsResponse::from([]),
            modelInfo: $attributes['model_info'] ?? [],
            license: $attributes['license'] ?? null,
            system: $attributes['system'] ?? null,
            messages: $attributes['messages'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'modelfile' => $this->modelfile,
            'parameters' => $this->parameters,
            'template' => $this->template,
            'details' => $this->details->toArray(),
            'model_info' => $this->modelInfo,
            'license' => $this->license,
            'system' => $this->system,
            'messages' => $this->messages,
        ], fn($value) => $value !== null && !(is_array($value) && empty($value)));
    }
}