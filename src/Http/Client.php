<?php

namespace Ollama\Http;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

class Client
{
    /**
     * @var string
     */
    public const string DEFAULT_URL = 'http://127.0.0.1:11434';

    /**
     * @var HttpClient
     */
    protected HttpClient $httpClient;

    /**
     * @param string $url
     * @param string|null $apiKey
     * @param array $options
     */
    public function __construct(
        protected string $url = self::DEFAULT_URL,
        protected ?string $apiKey = null,
        protected array $options = []
    ) {
        $this->url = rtrim($url, '/');

        if ($this->apiKey === null) {
            $this->apiKey = $_ENV['OLLAMA_API_KEY'] ?? null;
        }

        $this->httpClient = new HttpClient($this->getHttpOptions());
    }

    /**
     * @return array
     */
    protected function getHttpOptions(): array
    {
        $defaultOptions = [
            'connect_timeout' => 10,
        ];

        $options = array_replace_recursive($defaultOptions, $this->options);
        $options['base_uri'] = $this->url . '/api/';

        if ($this->apiKey !== null) {
            $options['headers'] = ['Authorization' => 'Bearer ' . $this->apiKey];
        }

        return $options;
    }

    /**
     * @param string $endpoint
     * @param array $parameters
     * @param boolean $parseJson
     * @return ResponseInterface|array
     */
    public function get(string $endpoint, array $parameters = [], bool $parseJson = true): ResponseInterface|array
    {
        $response = $this->httpClient->get($endpoint, [
            'query' => $parameters,
        ]);

        if ($parseJson === false) {
            return $response;
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @param string $endpoint
     * @param array $parameters
     * @param boolean $stream
     * @param boolean $parseJson
     * @return ResponseInterface|array
     */
    public function post(string $endpoint, array $parameters = [], bool $stream = false, bool $parseJson = true): ResponseInterface|array
    {
        $response = $this->httpClient->post($endpoint, [
            'json' => [
                ...$parameters,
                'stream' => $stream,
            ],
            'stream' => $stream,
        ]);

        if ($stream === true || $parseJson === false) {
            return $response;
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @param string $endpoint
     * @param array $parameters
     * @return ResponseInterface
     */
    public function delete(string $endpoint, array $parameters = []): ResponseInterface
    {
        return $this->httpClient->delete($endpoint, [
            'json' => $parameters,
        ]);
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
        if (empty($this->apiKey)) {
            throw new RuntimeException('API key is required for web search.');
        }

        $options = $this->getHttpOptions();
        $options['base_uri'] = 'https://ollama.com/api/';

        $client = new HttpClient($options);

        $response = $client->post('web_search', [
            'json' => [
                'query' => $query,
                'max_results' => min(max($maxResults, 1), 10),
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['results'] ?? [];
    }

    /**
     * @return boolean
     */
    public function isRunning(): bool
    {
        try {
            $response = $this->get('/', parseJson: false)->getBody()->getContents();
            return (strtolower($response) === 'ollama is running');
        } catch (Throwable $e) {
            return false;
        }
    }
}
