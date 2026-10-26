<?php

namespace Ollama\Responses\SystemOne;

class ScoreAnswer implements Answer
{
    /**
     * @param array<string, string> $legend
     * @param array<string, float>  $probabilities
     */
    public function __construct(
        public readonly float $score,
        public readonly array $legend,
        public readonly array $probabilities,
        public readonly float $confidence,
    ) {
        //
    }

    public static function from(array $data): static
    {
        return new static(
            score: (float) $data['score'],
            legend: $data['legend'] ?? [],
            probabilities: $data['probabilities'] ?? [],
            confidence: (float) $data['confidence'],
        );
    }

    public function getType(): string
    {
        return 'score';
    }
}
