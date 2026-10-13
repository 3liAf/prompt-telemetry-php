# prompt-telemetry-php

Zero-dependency PHP 8.4+ utility for calculating LLM token consumption, tokens per second, and estimated API expenditure across major model families (OpenAI, Anthropic, Gemini).

## Features

- **Cost Estimation**: Built-in pricing matrices for GPT-4o, Claude 3.5 Sonnet, Gemini 1.5 Pro, and Flash models.
- **Latency & Throughput**: Automatically derives output throughput (`tokens_per_sec`).
- **Zero Dependencies**: Pure PHP, strictly typed, PSR-4 compliant.

## Installation

```bash
composer require astrameld/prompt-telemetry-php
```

## Quick Start

```php
use Astrameld\PromptTelemetry\PromptMetrics;

$metrics = new PromptMetrics(
    model: 'gpt-4o',
    inputTokens: 1200,
    outputTokens: 450,
    durationMs: 950.0
);

echo "Total tokens: " . $metrics->getTotalTokens() . PHP_EOL;
echo "Throughput:   " . $metrics->getTokensPerSecond() . " tokens/sec" . PHP_EOL;
echo "Est. cost:    $" . $metrics->getEstimatedCostUsd() . PHP_EOL;
```

## Running Tests

```bash
php tests/PromptMetricsTest.php
```

## License

MIT License. See [LICENSE](LICENSE) for details.