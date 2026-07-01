<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

/**
 * Universal forecast schema: supports arbitrary number of outcomes,
 * not just "yes/no".
 */
function buildForecastFormat(): array
{
    return [
        'type' => 'object',
        'properties' => [
            'question' => [
                'type' => 'string',
                'description' => 'Reformulated forecast question',
            ],
            'outcomes' => [
                'type' => 'array',
                'description' => 'List of possible outcomes with probabilities. '
                    . 'For binary questions — two elements (e.g., "Yes"/"No"). '
                    . 'For multi-option questions — corresponding number of elements. '
                    . 'Sum of all probabilities must equal 100.',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'label' => [
                            'type' => 'string',
                            'description' => 'Outcome name, e.g., "Yes", "Candidate A", "Growth >5%"',
                        ],
                        'probability' => [
                            'type' => 'integer',
                            'description' => 'Outcome probability in percent (0-100)',
                            'minimum' => 0,
                            'maximum' => 100,
                        ],
                    ],
                    'required' => ['label', 'probability'],
                ],
                'minItems' => 2,
            ],
            'confidence' => [
                'type' => 'string',
                'description' => 'Model\'s overall confidence in the forecast',
                'enum' => ['low', 'medium', 'high'],
            ],
            'reasoning' => [
                'type' => 'string',
                'description' => 'Brief justification of the forecast (2-4 sentences)',
            ],
            'key_factors' => [
                'type' => 'array',
                'description' => 'Key factors that influenced the forecast',
                'items' => ['type' => 'string'],
            ],
            'time_horizon' => [
                'type' => 'string',
                'description' => 'Forecast time horizon, if applicable (e.g., "3 months", "by end of 2026"). Empty string if not applicable.',
            ],
        ],
        'required' => ['question', 'outcomes', 'confidence', 'reasoning', 'key_factors', 'time_horizon'],
    ];
}

function forecast(Ollama $ollama, string $question, ?array $outcomeHints = null): array
{
    $hint = $outcomeHints
        ? 'Use the following outcome options: ' . implode(', ', $outcomeHints) . '. '
        : 'Determine an appropriate set of outcomes independently based on the question phrasing '
            . '(for yes/no questions use two outcomes "Yes" and "No"; '
            . 'for multi-option questions list all significant options, '
            . 'add "Other" outcome if necessary). ';

    $response = $ollama->chat()->send([
        'model' => 'ornith:9b-q4_K_M',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a general-purpose analyst-forecaster. You can '
                    . 'estimate outcome probabilities for questions from any domain: '
                    . 'politics, economics, sports, technology, weather, social events, etc. '
                    . $hint
                    . 'The sum of probabilities for all listed outcomes must equal 100. '
                    . 'Be honest about uncertainty: use confidence to reflect how confident '
                    . 'you are in the forecast itself, not just in the numbers. '
                    . 'If the question is vaguely phrased, state the most reasonable '
                    . 'interpretation in the question field.',
            ],
            [
                'role' => 'user',
                'content' => $question,
            ],
        ],
        'format' => buildForecastFormat(),
        'stream' => false,
        'options' => [
            'temperature' => 0.2,
        ],
    ]);

    $forecast = json_decode($response->message->content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException(
            "Failed to parse model response:\n" . $response->message->content
        );
    }

    return $forecast;
}

function printForecast(array $forecast): void
{
    printf("Question: %s\n", $forecast['question']);

    if (!empty($forecast['time_horizon'])) {
        printf("Horizon: %s\n", $forecast['time_horizon']);
    }

    echo "\nOutcomes:\n";
    foreach ($forecast['outcomes'] as $outcome) {
        printf("  %-30s %d%%\n", $outcome['label'], $outcome['probability']);
    }

    printf(
        "\nConfidence: %s\n\nReasoning: %s\n\nKey factors:\n- %s\n",
        $forecast['confidence'],
        $forecast['reasoning'],
        implode("\n- ", $forecast['key_factors']),
    );
}

// Binary question — model determines outcomes
$forecast1 = forecast($ollama, 'Will the central bank lower its key interest rate at the next meeting?');
printForecast($forecast1);

echo str_repeat('-', 60) . "\n";

// Multi-option question without outcome hints
$forecast2 = forecast($ollama, 'Who will win the 2030 FIFA World Cup?');
printForecast($forecast2);

echo str_repeat('-', 60) . "\n";

// Question with explicitly specified outcome options
$forecast3 = forecast(
    $ollama,
    'What will be the exchange rate of the US dollar against the euro at the end of 2026?',
    ['Below 0.85', '0.85-0.95', '0.95-1.05', 'Above 1.05'],
);
printForecast($forecast3);
