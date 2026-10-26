<?php

declare(strict_types=1);

namespace Astrameld\PromptTelemetry;

/**
 * PromptMetrics calculates token usage, latency metrics, and estimated API expenditure.
 */
final class PromptMetrics
{
    /** @var array<string, array{input_cost_per_m: float, output_cost_per_m: float}> */
    private const MODEL_PRICING = [
        'gpt-4o' => ['input_cost_per_m' => 2.50, 'output_cost_per_m' => 10.00],
        'gpt-4o-mini' => ['input_cost_per_m' => 0.15, 'output_cost_per_m' => 0.60],
        'claude-3-5-sonnet' => ['input_cost_per_m' => 3.00, 'output_cost_per_m' => 15.00],
        'claude-3-5-haiku' => ['input_cost_per_m' => 0.80, 'output_cost_per_m' => 4.00],
        'gemini-1.5-pro' => ['input_cost_per_m' => 1.25, 'output_cost_per_m' => 5.00],
        'gemini-1.5-flash' => ['input_cost_per_m' => 0.075, 'output_cost_per_m' => 0.30],
        'deepseek-chat' => ['input_cost_per_m' => 0.14, 'output_cost_per_m' => 0.28],
        'deepseek-reasoner' => ['input_cost_per_m' => 0.55, 'output_cost_per_m' => 2.19],
    ];

    public function __construct(
        public readonly string $model,
        public readonly int $inputTokens,
        public readonly int $outputTokens,
        public readonly float $durationMs
    ) {
        if ($inputTokens < 0 || $outputTokens < 0 || $durationMs < 0) {
            throw new \InvalidArgumentException('Token counts and duration must be non-negative.');
        }
    }

    /**
     * Returns list of predefined model identifiers.
     *
     * @return list<string>
     */
    public static function getSupportedModels(): array
    {
        return array_keys(self::MODEL_PRICING);
    }

    /**
     * Checks if a model has explicit pricing configured.
     */
    public static function isSupported(string $model): bool
    {
        return isset(self::MODEL_PRICING[$model]);
    }

    public function getTotalTokens(): int
    {
        return $this->inputTokens + $this->outputTokens;
    }

    public function getTokensPerSecond(): float
    {
        if ($this->durationMs <= 0.0 || $this->outputTokens === 0) {
            return 0.0;
        }

        return ($this->outputTokens / ($this->durationMs / 1000.0));
    }

    public function getEstimatedCostUsd(): float
    {
        $pricing = self::MODEL_PRICING[$this->model] ?? ['input_cost_per_m' => 2.00, 'output_cost_per_m' => 10.00];

        $inputCost = ($this->inputTokens / 1_000_000.0) * $pricing['input_cost_per_m'];
        $outputCost = ($this->outputTokens / 1_000_000.0) * $pricing['output_cost_per_m'];

        return round($inputCost + $outputCost, 6);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'input_tokens' => $this->inputTokens,
            'output_tokens' => $this->outputTokens,
            'total_tokens' => $this->getTotalTokens(),
            'duration_ms' => round($this->durationMs, 2),
            'tokens_per_sec' => round($this->getTokensPerSecond(), 2),
            'estimated_cost_usd' => $this->getEstimatedCostUsd(),
        ];
    }
}
