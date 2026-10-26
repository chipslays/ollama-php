<?php

namespace Ollama\Responses\SystemOne;

class NoulAnswer implements Answer
{
    public function __construct(
        public readonly float $noul,
    ) {
        //
    }

    public static function from(array $data): static
    {
        return new static(
            noul: (float) $data['noul'],
        );
    }

    public function getType(): string
    {
        return 'noul';
    }
}
