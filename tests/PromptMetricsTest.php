<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/PromptMetrics.php';

use Astrameld\PromptTelemetry\PromptMetrics;

$metrics = new PromptMetrics(
    model: 'gpt-4o',
    inputTokens: 1000,
    outputTokens: 500,
    durationMs: 1250.0
);

assert($metrics->getTotalTokens() === 1500, 'Total tokens should be 1500');
assert($metrics->getTokensPerSecond() === 400.0, 'Tokens per second should be 400.0');
$cost = $metrics->getEstimatedCostUsd();
assert($cost > 0.007 && $cost < 0.008, 'Cost should be around $0.0075');

$data = $metrics->toArray();
assert($data['model'] === 'gpt-4o', 'Model mismatch');
assert($data['total_tokens'] === 1500, 'Token count mismatch');

echo "PromptMetricsTest: All assertions passed successfully in PHP " . PHP_VERSION . PHP_EOL;