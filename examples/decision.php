<?php

use Ollama\Ollama;
use Ollama\Requests\SystemOne\Questions;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

$response = $ollama->decision()->evaluate([
    'model' => 'tev1:0.8b',
    'state' => 'Our checkout has returned 500 errors since 9am.',
    'questions' => Questions::make()
        ->choice('label', 'Which label fits this ticket?', [
            'billing' => 'Payments and refunds',
            'bug' => 'Software errors',
            'account' => 'Login and account access',
        ])
        ->noul('is_bug', 'Is this a software bug?')
        ->score('severity', 'How severe is this issue?', ['minor', 'moderate', 'critical']),
]);

foreach ($response->answers as $name => $answer) {
    match ($answer->getType()) {
        'choice' => printf("%s - %s (%.2f%%)\n", $name, $answer->choice, $answer->confidence * 100),
        'noul' => printf("%s - true prob: %.2f\n", $name, $answer->noul),
        'score' => printf("%s - %.2f\n", $name, $answer->score),
    };
}
