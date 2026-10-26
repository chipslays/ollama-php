<?php

namespace Ollama;

use Ollama\Endpoints\Embed;
use Ollama\Endpoints\Blobs;
use Ollama\Endpoints\Chat;
use Ollama\Endpoints\Completions;
use Ollama\Endpoints\Models;
use Ollama\Endpoints\SystemOne;
use Ollama\Http\Client;
use RuntimeException;

class Ollama extends Client
{
    /**
     * Get a client instance (backward compatibility).
     *
     * @return Client
     */
    public function client(): Client
    {
        return $this;
    }

    /**
     * Get a completions instance.
     *
     * @return Completions
     */
    public function completions(): Completions
    {
        return new Completions($this);
    }

    /**
     * Get a chat instance.
     *
     * @return Chat
     */
    public function chat(): Chat
    {
        return new Chat($this);
    }

    /**
     * Get a system one instance.
     *
     * @return SystemOne
     */
    public function systemOne(): SystemOne
    {
        return new SystemOne($this);
    }

    /**
     * Alias for `systemOne()` method.
     *
     * @return SystemOne
     */
    public function decision(): SystemOne
    {
        return $this->systemOne();
    }

    /**
     * Get a models instance.
     *
     * @return Models
     */
    public function models(): Models
    {
        return new Models($this);
    }

    /**
     * Get a blobs instance.
     *
     * @return Blobs
     */
    public function blobs(): Blobs
    {
        return new Blobs($this);
    }

    /**
     * Get a embed instance.
     *
     * @return Embed
     */
    public function embed(): Embed
    {
        return new Embed($this);
    }

    /**
     * Get the Ollama version.
     *
     * @return string
     * @throws RuntimeException
     */
    public function version(): string
    {
        $data = $this->get('version');

        if (!is_array($data) || !isset($data['version']) || !is_string($data['version'])) {
            throw new RuntimeException('Unexpected response from Ollama when fetching version.');
        }

        return $data['version'];
    }
}
