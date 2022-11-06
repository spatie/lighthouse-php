<?php

namespace Spatie\Lighthouse;

use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Enums\FormFactor;
use Spatie\Lighthouse\Exceptions\AuditDoesNotExist;
use Spatie\Lighthouse\Support\Arr;

class LighthouseResult
{
    public function __construct(protected array $rawResults)
    {
    }

    public function configSettings(string $key = null): mixed
    {
        return Arr::get($this->rawResults['lhr']['configSettings'], $key);
    }

    public function networkThrottlingWasEnabled(): bool
    {
        return ! $this->configSettings('disableNetworkThrottling');
    }

    public function cpuThrottlingWasEnabled(): bool
    {
        return ! $this->configSettings('disableCpuThrottling');
    }

    public function formFactor(): FormFactor
    {
        return FormFactor::from($this->configSettings('emulatedFormFactor'));
    }

    public function userAgent(): string
    {
        return $this->configSettings('emulatedUserAgent');
    }

    public function headers(): array
    {
        return $this->configSettings('extraHeaders') ?? [];
    }

    public function scores(): array
    {
        $scores = [];

        foreach (Category::values() as $category) {
            $scores[$category] = intval($this->get("categories.$category.score") * 100);
        }

        return array_filter($scores);
    }

    public function get(string $path = null): mixed
    {
        $report = $this->rawResults['report'][0];

        return Arr::get($report, $path);
    }

    public function html(): string
    {
        return $this->rawResults['report'][1];
    }

    public function saveHtml(string $path): self
    {
        file_put_contents($path, $this->html());

        return $this;
    }

    public function rawResults(string $key = null): mixed
    {
        return Arr::get($this->rawResults, $key);
    }

    public function audits(): array
    {
        return $this->rawResults['report'][0]['audits'];
    }

    public function audit(string $auditName): array
    {
        $audit = $this->audits()[$auditName] ?? null;

        if (is_null($audit)) {
            throw AuditDoesNotExist::make($auditName);
        }

        return $audit;
    }

    public function auditNames(): array
    {
        return array_keys($this->audits());
    }

    public function formattedFirstContentfulPaint(): string
    {
        return $this->audit('first-contentful-paint')['displayValue'];
    }

    public function firstContentfulPaintInMs(): float
    {
        return $this->audit('first-contentful-paint')['numericValue'];
    }

    public function formattedLargestContentfulPaint(): string
    {
        return $this->audit('largest-contentful-paint')['displayValue'];
    }

    public function largestContentfulPaintInMs(): float
    {
        return $this->audit('largest-contentful-paint')['numericValue'];
    }

    public function formattedSpeedIndex(): string
    {
        return $this->audit('speed-index')['displayValue'];
    }

    public function speedIndexInMs(): float
    {
        return $this->audit('speed-index')['numericValue'];
    }

    public function formattedTotalBlockingTime(): string
    {
        return $this->audit('total-blocking-time')['displayValue'];
    }

    public function totalBlockingTimeInMs(): float
    {
        return $this->audit('total-blocking-time')['numericValue'];
    }

    public function formattedTimeToInteractive(): string
    {
        return $this->audit('interactive')['displayValue'];
    }

    public function timeToInteractiveInMs(): float
    {
        return $this->audit('interactive')['numericValue'];
    }

    public function formattedCumulativeLayoutShift(): float
    {
        return $this->audit('cumulative-layout-shift')['displayValue'];
    }

    public function cumulativeLayoutShift(): float
    {
        return $this->audit('cumulative-layout-shift')['numericValue'];
    }

    public function lighthouseVersion(): string
    {
        return $this->rawResults('lhr.lighthouseVersion');
    }

    public function totalPageSizeInBytes(): int
    {
        return $this->audit('total-byte-weight')['numericValue'];
    }

    public function benchmarkIndex(): int
    {
        return $this->rawResults('lhr.environment.benchmarkIndex');
    }

    public function budgetResults(): array
    {
        $budgetResults = [];

        foreach ($this->auditNames() as $auditName) {
            if (str_ends_with($auditName, '-budget')) {
                $budgetResults[$auditName] = $this->audit($auditName);
            }
        }

        return $budgetResults;
    }
}
