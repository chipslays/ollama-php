<?php

namespace Ollama\Endpoints;

use Exception;
use Ollama\Http\Client;
use Ollama\Responses\Models\CreateModelResponse;
use Ollama\Responses\Models\AllModelsResponse;
use Ollama\Responses\Models\RunningModelsResponse;
use Ollama\Responses\Models\PullModelResponse;
use Ollama\Responses\Models\PushModelResponse;
use Ollama\Responses\Models\ShowModelResponse;
use Ollama\Responses\StreamResponse;

class Models
{
    public function __construct(protected Client $client)
    {
        //
    }

    /**
     * Fetch a list of models and their details.
     *
     * @see https://docs.ollama.com/api/tags
     *
     * @return AllModelsResponse
     */
    public function all(): AllModelsResponse
    {
        $response = $this->client->get('tags');

        return AllModelsResponse::from($response);
    }

    /**
     * Retrieve a list of models that are currently running.
     *
     * @see https://docs.ollama.com/api/ps
     *
     * @return RunningModelsResponse
     */
    public function running(): RunningModelsResponse
    {
        $response = $this->client->get('ps');

        return RunningModelsResponse::from($response);
    }

    /**
     * Show model details.
     *
     * @see https://docs.ollama.com/api-reference/show-model-details
     *
     * @param string $modelName
     * @param boolean $verbose
     * @return ShowModelResponse
     */
    public function show(string $modelName, bool $verbose = false): ShowModelResponse
    {
        $response = $this->client->post('show', [
            'name' => $modelName,
            'verbose' => $verbose,
        ]);

        return ShowModelResponse::from($response);
    }

    /**
     * Create a model.
     *
     * @see https://docs.ollama.com/api/create
     *
     * @param array $parameters
     * @return CreateModelResponse
     */
    public function create(array $parameters): CreateModelResponse
    {
        $response = $this->client->post('create', $parameters, false);

        return CreateModelResponse::from($response);
    }

    /**
     * @see https://docs.ollama.com/api/create
     *
     * @param array $parameters
     * @return StreamResponse
     */
    public function createWithStream(array $parameters): StreamResponse
    {
        $response = $this->client->post('create', $parameters, true);

        return new StreamResponse(CreateModelResponse::class, $response);
    }

    /**
     * Copy a model.
     *
     * @see https://docs.ollama.com/api/copy
     *
     * @param string $source
     * @param string $destination
     * @return boolean
     */
    public function copy(string $source, string $destination): bool
    {
        try {
            $response = $this->client->post('copy', [
                'source' => $source,
                'destination' => $destination,
            ], parseJson: false);

            return in_array($response->getStatusCode(), [200, 201]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete a model.
     *
     * @see https://docs.ollama.com/api/delete
     *
     * @param string $modelName
     * @return boolean
     */
    public function delete(string $modelName): bool
    {
        try {
            $response = $this->client->delete('delete', [
                'name' => $modelName,
            ]);

            return in_array($response->getStatusCode(), [200, 204]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Pull a model.
     *
     * @see https://docs.ollama.com/api/pull
     *
     * @param string $modelName
     * @param boolean $insecure
     * @return PullModelResponse
     */
    public function pull(string $modelName, bool $insecure = false): PullModelResponse
    {
        $response = $this->client->post('pull', [
            'name' => $modelName,
            'insecure' => $insecure,
        ]);

        return PullModelResponse::from($response);
    }

    /**
     * @see https://docs.ollama.com/api/pull
     *
     * @param string $modelName
     * @param boolean $insecure
     * @return StreamResponse
     */
    public function pullWithStream(string $modelName, bool $insecure = false): StreamResponse
    {
        $response = $this->client->post('pull', [
            'name' => $modelName,
            'insecure' => $insecure,
        ], true);

        return new StreamResponse(PullModelResponse::class, $response);
    }

    /**
     * Push a model.
     *
     * @see https://docs.ollama.com/api/push
     *
     * @param string $modelName
     * @param boolean $insecure
     * @return PushModelResponse
     */
    public function push(string $modelName, bool $insecure = false): PushModelResponse
    {
        $response = $this->client->post('push', [
            'name' => $modelName,
            'insecure' => $insecure,
        ]);

        return PushModelResponse::from($response);
    }

    /**
     * @see https://docs.ollama.com/api/push
     *
     * @param string $modelName
     * @param boolean $insecure
     * @return StreamResponse
     */
    public function pushWithStream(string $modelName, bool $insecure = false): StreamResponse
    {
        $response = $this->client->post('push', [
            'name' => $modelName,
            'insecure' => $insecure,
        ], true);

        return new StreamResponse(PushModelResponse::class, $response);
    }

    /**
     * Load a model to memory.
     *
     * @param string $modelName
     * @return boolean
     */
    public function load(string $modelName): bool
    {
        $response = $this->client->post('generate', [
            'model' => $modelName,
        ]);

        return $response['model'] === $modelName && $response['done'] === true;
    }

    /**
     * Unload a model from memory.
     *
     * @param string $modelName
     * @return boolean
     */
    public function unload(string $modelName): bool
    {
        $response = $this->client->post('generate', [
            'model' => $modelName,
            'keep_alive' => 0,
        ]);

        return $response['model'] === $modelName && $response['done'] === true;
    }
}