<?php

namespace Spatie\Lighthouse;

use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Enums\FormFactor;
use Spatie\Lighthouse\Exceptions\AuditDoesNotExist;
use Spatie\Lighthouse\Support\Arr;

class LighthouseResult
{
    public function __construct(protected array $rawResults = []) {}

    public function setJsonReport(array $jsonReport): self
    {
        $this->rawResults['report'][0] = $jsonReport;

        return $this;
    }

    public function setHtmlReport(string $htmlReport): self
    {
        $this->rawResults['report'][1] = $htmlReport;

        return $this;
    }

    public function configSettings(?string $key = null): mixed
    {
        return Arr::get($this->rawResults['report'][0]['configSettings'], $key);
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
        return FormFactor::from($this->configSettings('formFactor'));
    }

    public function userAgent(): string
    {
        return $this->configSettings('emulatedUserAgent');
    }

    public function headers(): array
    {
        return $this->configSettings('extraHeaders') ?? [];
    }

    public function scores(Category|string|null $category = null): array|int|null
    {
        if ($category && is_string($category)) {
            $category = Category::fromString($category);
        }

        $scores = [];

        foreach (Category::values() as $categoryName) {
            $value = $this->get("categories.$categoryName.score");

            if (! is_null($value)) {
                $value = intval($this->get("categories.$categoryName.score") * 100);
            }

            $scores[$categoryName] = $value;
        }

        if ($category) {
            return $scores[$category->value];
        }

        return array_filter($scores);
    }

    public function get(?string $path = null): mixed
    {
        $report = $this->rawResults['report'][0];

        return Arr::get($report, $path);
    }

    public function toArray(?string $path = null): mixed
    {
        return $this->get($path);
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

    public function rawResults(?string $key = null): mixed
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

    public function formattedFirstContentfulPaint(): ?string
    {
        return $this->audit('first-contentful-paint')['displayValue'] ?? null;
    }

    public function firstContentfulPaintInMs(): ?float
    {
        return $this->audit('first-contentful-paint')['numericValue'] ?? null;
    }

    public function formattedLargestContentfulPaint(): ?string
    {
        return $this->audit('largest-contentful-paint')['displayValue'] ?? null;
    }

    public function largestContentfulPaintInMs(): ?float
    {
        return $this->audit('largest-contentful-paint')['numericValue'] ?? null;
    }

    public function formattedSpeedIndex(): ?string
    {
        return $this->audit('speed-index')['displayValue'] ?? null;
    }

    public function speedIndexInMs(): ?float
    {
        return $this->audit('speed-index')['numericValue'] ?? null;
    }

    public function formattedTotalBlockingTime(): ?string
    {
        return $this->audit('total-blocking-time')['displayValue'] ?? null;
    }

    public function totalBlockingTimeInMs(): ?float
    {
        return $this->audit('total-blocking-time')['numericValue'] ?? null;
    }

    public function formattedTimeToInteractive(): ?string
    {
        return $this->audit('interactive')['displayValue'] ?? null;
    }

    public function timeToInteractiveInMs(): ?float
    {
        return $this->audit('interactive')['numericValue'] ?? null;
    }

    public function formattedCumulativeLayoutShift(): ?float
    {
        return $this->audit('cumulative-layout-shift')['displayValue'] ?? null;
    }

    public function cumulativeLayoutShift(): ?float
    {
        return $this->audit('cumulative-layout-shift')['numericValue'] ?? null;
    }

    public function lighthouseVersion(): string
    {
        return $this->rawResults('report.0.lighthouseVersion');
    }

    public function totalPageSizeInBytes(): ?int
    {
        return $this->audit('total-byte-weight')['numericValue'] ?? null;
    }

    public function benchmarkIndex(): ?int
    {
        return $this->rawResults('report.0.environment.benchmarkIndex') ?? null;
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
