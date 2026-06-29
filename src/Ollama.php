<?php

namespace Ollama;

use Ollama\Endpoints\Embed;
use Ollama\Endpoints\Blobs;
use Ollama\Endpoints\Chat;
use Ollama\Endpoints\Completions;
use Ollama\Endpoints\Models;
use Ollama\Http\Client;

class Ollama
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @param string $url
     * @param string|null $apiKey
     * @param array $options
     */
    public function __construct(
        string $url = Client::DEFAULT_URL,
        ?string $apiKey = null,
        array $options = []
    ) {
        $this->client = new Client($url, $apiKey, $options);
    }

    /**
     * Get a client instance.
     *
     * @return Client
     */
    public function client(): Client
    {
        return $this->client;
    }

    /**
     * Get a completions instance.
     *
     * @return Completions
     */
    public function completions(): Completions
    {
        return new Completions($this->client);
    }

    /**
     * Get a chat instance.
     *
     * @return Chat
     */
    public function chat(): Chat
    {
        return new Chat($this->client);
    }

    /**
     * Get a models instance.
     *
     * @return Models
     */
    public function models(): Models
    {
        return new Models($this->client);
    }

    /**
     * Get a blobs instance.
     *
     * @return Blobs
     */
    public function blobs(): Blobs
    {
        return new Blobs($this->client);
    }

    /**
     * Get a embed instance.
     *
     * @return Embed
     */
    public function embed(): Embed
    {
        return new Embed($this->client);
    }

    /**
     * Get the Ollama version.
     *
     * @return string
     * */
    public function version(): string
    {
        return $this->client->get('version')['version'];
    }

    /**
     * @see https://docs.ollama.com/capabilities/web-search
     *
     * @param string $query
     * @param integer $maxResults
     * @return array
     */
    public function webSearch(string $query, int $maxResults = 5): array
    {
        return $this->client->webSearch($query, $maxResults);
    }

    /**
     * @return boolean
     */
    public function isRunning(): bool
    {
        return $this->client->isRunning();
    }
}