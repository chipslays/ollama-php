<?php

namespace Ollama\Responses\SystemOne;

class Usage
{
    public function __construct(
        public readonly int $inputTokens,
        public readonly int $outputTokens,
    ) {
        //
    }

    public static function from(array $data): static
    {
        return new static(
            inputTokens: (int) ($data['input_tokens'] ?? 0),
            outputTokens: (int) ($data['output_tokens'] ?? 0),
        );
    }
}
