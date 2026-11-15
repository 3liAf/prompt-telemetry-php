# prompt-telemetry-php

[![CI](https://github.com/3liAf/prompt-telemetry-php/actions/workflows/ci.yml/badge.svg)](https://github.com/3liAf/prompt-telemetry-php/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4.svg?logo=php&logoColor=white)](https://www.php.net/)
[![Release](https://img.shields.io/github/v/release/3liAf/prompt-telemetry-php)](https://github.com/3liAf/prompt-telemetry-php/releases)

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