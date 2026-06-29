<?php

namespace Ollama\Responses;

use Exception;
use Generator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StreamResponse
{
    public function __construct(
        protected readonly string $responseClass,
        protected readonly ResponseInterface $response,
    )
    {
        //
    }

    public function getIterator(): Generator
    {
        while (!$this->response->getBody()->eof()) {
            $line = $this->read($this->response->getBody());

            if (empty($line)) {
                continue;
            }

            $response = json_decode($line, true, flags: JSON_THROW_ON_ERROR);

            if (isset($response['error'])) {
                throw new Exception($response['error']);
            }

            yield $this->responseClass::from($response);
        }
    }

    protected function read(StreamInterface $stream): string
    {
        $buffer = '';

        while (!$stream->eof()) {
            if ('' === ($byte = $stream->read(1))) {
                return $buffer;
            }

            $buffer .= $byte;

            if ($byte === "\n") {
                break;
            }
        }

        return $buffer;
    }
}