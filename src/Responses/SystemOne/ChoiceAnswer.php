<?php

namespace Ollama\Responses\SystemOne;

class ChoiceAnswer implements Answer
{
    /**
     * @param array<string, float> $probabilities
     */
    public function __construct(
        public readonly string $choice,
        public readonly array $probabilities,
        public readonly float $confidence,
    ) {
        //
    }

    public static function from(array $data): static
    {
        return new static(
            choice: $data['choice'],
            probabilities: $data['probabilities'] ?? [],
            confidence: (float) $data['confidence'],
        );
    }

    public function getType(): string
    {
        return 'choice';
    }
}
