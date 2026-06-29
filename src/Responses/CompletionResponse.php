<?php

namespace Ollama\Responses;

use Ollama\Responses\AbstractResponse;
use Ollama\Responses\LogprobResponse;

class CompletionResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $model,
        public readonly string $createdAt,
        public readonly string $response,
        public readonly bool $done,
        public readonly ?string $doneReason,
        public readonly int|float|null $totalDuration,
        public readonly ?int $loadDuration,
        public readonly ?int $promptEvalCount,
        public readonly ?int $promptEvalDuration,
        public readonly ?int $evalCount,
        public readonly ?int $evalDuration,
        public readonly ?string $thinking = null,
        public readonly ?array $logprobs = null,
    )
    {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            model: $attributes['model'],
            createdAt: $attributes['created_at'],
            response: $attributes['response'],
            done: $attributes['done'],
            doneReason: $attributes['done_reason'] ?? null,
            totalDuration: $attributes['total_duration'] ?? null,
            loadDuration: $attributes['load_duration'] ?? null,
            promptEvalCount: $attributes['prompt_eval_count'] ?? null,
            promptEvalDuration: $attributes['prompt_eval_duration'] ?? null,
            evalCount: $attributes['eval_count'] ?? null,
            evalDuration: $attributes['eval_duration'] ?? null,
            thinking: $attributes['thinking'] ?? null,
            logprobs: isset($attributes['logprobs'])
                ? array_map(
                    fn(array $logprob) => LogprobResponse::from($logprob),
                    $attributes['logprobs']
                )
                : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'model' => $this->model,
            'created_at' => $this->createdAt,
            'response' => $this->response,
            'done' => $this->done,
            'done_reason' => $this->doneReason,
            'total_duration' => $this->totalDuration,
            'load_duration' => $this->loadDuration,
            'prompt_eval_count' => $this->promptEvalCount,
            'prompt_eval_duration' => $this->promptEvalDuration,
            'eval_count' => $this->evalCount,
            'eval_duration' => $this->evalDuration,
            'thinking' => $this->thinking,
            'logprobs' => $this->logprobs
                ? array_map(
                    fn(LogprobResponse $response): array => $response->toArray(),
                    $this->logprobs
                )
                : null,
        ], fn($value) => $value !== null);
    }
}