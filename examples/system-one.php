<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$response = $ollama->systemOne()->ask([
    'model' => 'tev1:0.8b',
    'state' => 'Our checkout has returned 500 errors since 9am.',
    'questions' => [
        'label' => [
            'type' => 'choice',
            'instructions' => 'Which label fits this ticket?',
            'criteria' => [
                'billing' => 'Payments and refunds',
                'bug'     => 'Software errors',
                'account' => 'Login and account access',
            ],
        ],
        'is_bug' => [
            'type' => 'noul',
            'instructions' => 'Is this a software bug?',
        ],
        'severity' => [
            'type' => 'score',
            'instructions' => 'How severe is this issue?',
            'criteria' => ['minor', 'moderate', 'critical'],
        ],
    ],
]);

foreach ($response->answers as $name => $answer) {
    match ($answer->getType()) {
        'choice' => printf("%s - %s (%.2f%%)\n", $name, $answer->choice, $answer->confidence * 100),
        'noul' => printf("%s - true prob: %.2f\n", $name, $answer->noul),
        'score' => printf("%s - %.2f\n", $name, $answer->score),
    };
}
