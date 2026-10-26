<?php

namespace Ollama\Responses\SystemOne;

use UnexpectedValueException;

class SystemOneResponse
{
    /**
     * @param array<string, Answer> $answers
     */
    public function __construct(
        public readonly string $model,
        public readonly array $answers,
        public readonly Usage $usage,
    ) {
        //
    }

    public static function from(array $data): static
    {
        $answers = [];

        foreach ($data['answers'] ?? [] as $name => $raw) {
            $answers[$name] = match ($raw['type'] ?? null) {
                'choice' => ChoiceAnswer::from($raw),
                'noul'   => NoulAnswer::from($raw),
                'score'  => ScoreAnswer::from($raw),
                default  => throw new UnexpectedValueException(sprintf(
                    'Unknown System One answer type "%s" for question "%s".',
                    $raw['type'] ?? 'null',
                    $name,
                )),
            };
        }

        return new static(
            model: $data['model'],
            answers: $answers,
            usage: Usage::from($data['usage']),
        );
    }

    public function answer(string $name): ?Answer
    {
        return $this->answers[$name] ?? null;
    }
}
