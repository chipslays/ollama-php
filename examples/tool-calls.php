<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

function getWeather(string $city): array
{
    return [
        'city' => $city,
        'temperature' => 21,
        'condition' => 'Sunny',
    ];
}

$messages = [
    ['role' => 'system', 'content' => 'You are a helpful assistant that can call tools.'],
    ['role' => 'user', 'content' => 'What\'s the weather like in Moscow?'],
];

$tools = [
    [
        'type' => 'function',
        'function' => [
            'name' => 'get_weather',
            'description' => 'Returns the current weather in a given city.',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'city' => [
                        'type' => 'string',
                        'description' => 'The name of the city.',
                    ],
                ],
                'required' => ['city'],
            ],
        ],
    ],
];

$response = $ollama->chat()->send([
    'model' => 'ornith:9b-q4_K_M',
    'messages' => $messages,
    'tools' => $tools,
]);

$message = $response->message;

$messages[] = $message->toArray();

if (!empty($message->toolCalls)) {
    foreach ($message->toolCalls as $toolCall) {
        $function = $toolCall->function;
        $name = $function->name;
        $arguments = $function->arguments;

        $result = match ($name) {
            'get_weather' => getWeather($arguments['city'] ?? ''),
            default => ['error' => "Unknown tool: {$name}"],
        };

        $messages[] = [
            'role' => 'tool',
            'content' => json_encode($result, JSON_UNESCAPED_UNICODE),
        ];
    }

    $finalResponse = $ollama->chat()->send([
        'model' => 'ornith:9b-q4_K_M',
        'messages' => $messages,
        'tools' => $tools,
    ]);

    echo $finalResponse->message->content;
} else {
    echo $message->content;
}
