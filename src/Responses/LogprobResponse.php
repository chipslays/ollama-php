<?php

namespace Ollama\Responses;

class LogprobResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $token,
        public readonly float $logprob,
        public readonly ?array $bytes = null,
        public readonly ?array $topLogprobs = null,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            token: $attributes['token'],
            logprob: $attributes['logprob'],
            bytes: $attributes['bytes'] ?? null,
            topLogprobs: isset($attributes['top_logprobs'])
                ? array_map(
                    fn(array $topLogprob) => TokenLogprobResponse::from($topLogprob),
                    $attributes['top_logprobs']
                )
                : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'token' => $this->token,
            'logprob' => $this->logprob,
            'bytes' => $this->bytes,
            'top_logprobs' => $this->topLogprobs
                ? array_map(
                    fn(TokenLogprobResponse $response): array => $response->toArray(),
                    $this->topLogprobs
                )
                : null,
        ], fn($value) => $value !== null);
    }
}