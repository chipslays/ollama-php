<?php

namespace Ollama\Responses;

class TokenLogprobResponse extends AbstractResponse
{
    protected function __construct(
        public readonly string $token,
        public readonly float $logprob,
        public readonly ?array $bytes = null,
    ) {
        //
    }

    public static function from(array $attributes): static
    {
        return new static(
            token: $attributes['token'],
            logprob: $attributes['logprob'],
            bytes: $attributes['bytes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'token' => $this->token,
            'logprob' => $this->logprob,
            'bytes' => $this->bytes,
        ], fn($value) => $value !== null);
    }
}