<?php

use Ollama\Ollama;

require __DIR__ . '/../vendor/autoload.php';

$ollama = new Ollama;

function buildContactFormat(): array
{
    return [
        'type' => 'object',
        'properties' => [
            'emails' => [
                'type' => 'array',
                'description' => 'ONLY email addresses (containing an "@" symbol). '
                    . 'Do NOT put phone numbers here. All email addresses found anywhere '
                    . 'in the text, without duplicates.',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'value' => [
                            'type' => 'string',
                            'description' => 'Email address (must contain "@"), as it appears in the source',
                        ],
                        'context' => [
                            'type' => 'string',
                            'description' => 'Short surrounding text/description near the email. Empty string if none.',
                        ],
                    ],
                    'required' => ['value', 'context'],
                ],
            ],
            'phones' => [
                'type' => 'array',
                'description' => 'ONLY phone numbers (digit sequences, no "@" symbol). '
                    . 'Do NOT put email addresses here. All phone numbers found anywhere '
                    . 'in the text, without duplicates.',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'raw' => [
                            'type' => 'string',
                            'description' => 'Phone number (must not contain "@"), as it appears in the source',
                        ],
                        'context' => [
                            'type' => 'string',
                            'description' => 'Short surrounding text/description near the number. Empty string if none.',
                        ],
                    ],
                    'required' => ['raw', 'context'],
                ],
            ],
        ],
        'required' => ['emails', 'phones'],
    ];
}

function extractAllContactsLlm(Ollama $ollama, string $htmlOrText): array
{
    $response = $ollama->chat()->send([
        'model' => 'ornith:9b-q4_K_M',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a data extractor. You are given raw HTML or text. '
                    . 'Extract ALL email addresses and ALL phone numbers found anywhere '
                    . 'in the content, including inside scripts, attributes, comments — '
                    . 'everywhere. Do not filter or judge relevance — extract every single '
                    . 'one you find, no matter where it appears or what it looks like it\'s for. '
                    . 'Do not invent data that is not present. Remove exact duplicates only. '
                    . 'IMPORTANT: keep the two lists strictly separate. The "emails" array must '
                    . 'contain ONLY strings with an "@" symbol. The "phones" array must contain '
                    . 'ONLY digit sequences with no "@" symbol. Never put the same item in both '
                    . 'arrays, and never put an email in "phones" or a phone number in "emails". '
                    . 'For each item, also provide a short "context" — whatever surrounding '
                    . 'text explains what it is (e.g. a label, a nearby sentence, a variable '
                    . 'name it was assigned to). If there is truly nothing nearby that gives '
                    . 'context, leave it as an empty string.',
            ],
            [
                'role' => 'user',
                'content' => $htmlOrText,
            ],
        ],
        'format' => buildContactFormat(),
        'stream' => false,
        'options' => [
            'temperature' => 0,
        ],
    ]);

    $result = json_decode($response->message->content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException("Failed to parse model response:\n" . $response->message->content);
    }

    return sanitizeContacts($result);
}

function sanitizeContacts(array $result): array
{
    // из emails оставляем только то, что реально содержит @
    $result['emails'] = array_values(array_filter(
        $result['emails'],
        fn(array $e) => str_contains($e['value'], '@')
    ));

    // из phones убираем всё, что похоже на email
    $result['phones'] = array_values(array_filter(
        $result['phones'],
        fn(array $p) => !str_contains($p['raw'], '@')
    ));

    // на всякий случай убираем дубли внутри каждого массива по значению
    $seen = [];
    $result['emails'] = array_values(array_filter($result['emails'], function ($e) use (&$seen) {
        if (isset($seen['email_' . $e['value']])) {
            return false;
        }
        $seen['email_' . $e['value']] = true;
        return true;
    }));

    $seen = [];
    $result['phones'] = array_values(array_filter($result['phones'], function ($p) use (&$seen) {
        if (isset($seen['phone_' . $p['raw']])) {
            return false;
        }
        $seen['phone_' . $p['raw']] = true;
        return true;
    }));

    return $result;
}

function printContacts(array $result): void
{
    echo "Emails:\n";
    foreach ($result['emails'] as $email) {
        printf("  %-35s %s\n", $email['value'], $email['context']);
    }

    echo "\nPhones:\n";
    foreach ($result['phones'] as $phone) {
        printf("  %-20s %s\n", $phone['raw'], $phone['context']);
    }
}

$html = <<<HTML
<html>
<body>
<div class="footer">
    <p>Contact us: <a href="mailto:sales@example.com">sales@example.com</a></p>
    <p>Human Resources: hr@example.com, phone: +7 (926) 123-45-67</p>
    <p>Hotline: 8-800-555-35-35 (toll-free in Russia)</p>
    <p>Fax: +7 495 123 45 68</p>
    <script>var trackingEmail = "noreply@analytics.internal";</script>
</div>
</body>
</html>
HTML;

$contacts = extractAllContactsLlm($ollama, $html);
printContacts($contacts);
