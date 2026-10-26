<?php

namespace Ollama\Requests\SystemOne;

use InvalidArgumentException;
use JsonSerializable;

class Question implements JsonSerializable
{
    private function __construct(
        public readonly string $type,
        public readonly string|array $instructions,
        public readonly ?array $criteria = null,
    ) {
        //
    }

    /**
     * Pick one option from a set. At least 2, at most 255 criteria.
     *
     * @param array<string, string|null> $criteria Option key => description.
     */
    public static function choice(string|array $instructions, array $criteria): self
    {
        if (count($criteria) < 2) {
            throw new InvalidArgumentException('Choice question requires at least 2 criteria.');
        }

        return new self('choice', $instructions, $criteria);
    }

    /**
     * Yes/no question. Criteria are optional per-outcome descriptions.
     *
     * @param array{false?: string, true?: string} $criteria
     */
    public static function noul(string|array $instructions, array $criteria = []): self
    {
        return new self('noul', $instructions, $criteria ?: null);
    }

    /**
     * Score question. Criteria are ordered lowest (0) to highest.
     * Between 2 and 10 criteria.
     *
     * @param list<string> $criteria
     */
    public static function score(string|array $instructions, array $criteria): self
    {
        $criteria = array_values($criteria);

        if (count($criteria) < 2 || count($criteria) > 10) {
            throw new InvalidArgumentException('Score question requires between 2 and 10 criteria.');
        }

        return new self('score', $instructions, $criteria);
    }

    public function toArray(): array
    {
        $data = [
            'type' => $this->type,
            'instructions' => $this->instructions,
        ];

        if ($this->criteria !== null) {
            $data['criteria'] = $this->criteria;
        }

        return $data;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
