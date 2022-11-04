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
}
