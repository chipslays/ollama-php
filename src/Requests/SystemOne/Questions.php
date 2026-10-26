<?php

namespace Ollama\Requests\SystemOne;

use InvalidArgumentException;
use JsonSerializable;

/**
 * A named collection of questions for the System One API.
 *
 * Each question is registered under a unique name and answered by the
 * model in a single request. Use {@see Questions::make()} to create an
 * instance, chain `choice()`, `noul()`, and `score()` calls, then pass
 * the result as the `questions` parameter to
 * {@see \Ollama\Endpoints\SystemOne::evaluate()}.
 *
 * Example:
 *
 *     Questions::make()
 *         ->choice('label', 'Which label fits this ticket?', [
 *             'billing' => 'Payments and refunds',
 *             'bug' => 'Software errors',
 *         ])
 *         ->noul('is_bug', 'Is this a software bug?');
 *
 * @see https://docs.ollama.com/capabilities/decision
 */
class Questions implements JsonSerializable
{
    /** @var Question[] */
    private array $items = [];

    /**
     * Create a new, empty collection of questions.
     *
     * @return self
     */
    public static function make(): self
    {
        return new self();
    }

    /**
     * Add a "choice" question: pick one option from a set of criteria.
     *
     * At least 2 and at most 255 criteria are allowed. Option keys must
     * be non-empty strings; ties follow the model's option order.
     *
     * @param string $name Unique question name (up to 100 ASCII letters, digits, `_`, `.`, `-`).
     * @param string|array $instructions Question prompt, or an object/array serialized as JSON.
     * @param array $criteria Option key => description (or null for a bare label).
     * @return self
     * @throws InvalidArgumentException If fewer than 2 criteria are provided.
     */
    public function choice(string $name, string|array $instructions, array $criteria): self
    {
        return $this->add($name, Question::choice($instructions, $criteria));
    }

    /**
     * Add a "noul" (yes/no) question.
     *
     * Criteria are optional per-outcome descriptions, keyed by `false`
     * and `true`; omitted entries use the model's defaults.
     *
     * @param string $name Unique question name.
     * @param string|array $instructions Question prompt, or an object/array serialized as JSON.
     * @param array $criteria Optional descriptions for the false and true outcomes.
     * @return self
     */
    public function noul(string $name, string|array $instructions, array $criteria = []): self
    {
        return $this->add($name, Question::noul($instructions, $criteria));
    }

    /**
     * Add a "score" question: rate the state on a discrete scale.
     *
     * Criteria are ordered from the lowest score (index 0) to the highest,
     * defining a scale from 0 to count(criteria) - 1. Between 2 and 10
     * criteria are allowed.
     *
     * @param string $name Unique question name.
     * @param string|array $instructions Question prompt, or an object/array serialized as JSON.
     * @param array $criteria Ordered scale descriptions, lowest to highest.
     * @return self
     * @throws InvalidArgumentException If fewer than 2 or more than 10 criteria are provided.
     */
    public function score(string $name, string|array $instructions, array $criteria): self
    {
        return $this->add($name, Question::score($instructions, $criteria));
    }

    /**
     * Register a question under the given name.
     *
     * @param string $name
     * @param Question $question
     * @return self
     * @throws InvalidArgumentException If a question with the same name already exists.
     */
    private function add(string $name, Question $question): self
    {
        if (isset($this->items[$name])) {
            throw new InvalidArgumentException("Question \"{$name}\" is already defined.");
        }

        $this->items[$name] = $question;

        return $this;
    }

    /**
     * Convert the collection to the array shape expected by the System One API.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_map(
            static fn (Question $q) => $q->toArray(),
            $this->items,
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
