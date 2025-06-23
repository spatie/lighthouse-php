<?php

namespace Spatie\Lighthouse;

use Spatie\Lighthouse\Enums\Category;
use Spatie\Lighthouse\Enums\FormFactor;
use Spatie\Lighthouse\Exceptions\CouldNotRunLighthouse;
use Spatie\Lighthouse\Exceptions\InvalidUrl;
use Spatie\Lighthouse\Exceptions\LighthouseReportedError;
use Spatie\Lighthouse\Support\Arr;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class Lighthouse
{
    protected string $url;

    protected array $lighthouseConfig = [];

    protected array $chromeOptions = [];

    protected int $timeoutInSeconds = 60;

    protected ?array $onlyAudits = null;

    public static function url(string $url): self
    {
        return new self($url);
    }

    public function __construct(string $url)
    {
        $validatedUrl = filter_var($url, FILTER_VALIDATE_URL);

        if (! $validatedUrl) {
            throw InvalidUrl::make($url);
        }

        $this->url = $url;
        $this->lighthouseConfig = $this->defaultLighthouseConfig();
        $this->chromeOptions = $this->defaultChromeOptions();
    }

    public function defaultLighthouseConfig(): array
    {
        return [
            'extends' => 'lighthouse:default',
            'settings' => [
                'onlyCategories' => Category::values(),
                'formFactor' => 'desktop',
                'output' => ['json', 'html'],
                'gatherMode' => false,
                'disableNetworkThrottling' => true,
                'disableCpuThrottling' => true,
                'throttlingMethod' => 'provided',
                'screenEmulation' => [
                    'disabled' => true,
                ],
            ],
        ];
    }

    public function defaultChromeOptions(): array
    {
        return [
            'chromeFlags' => [
                '--headless',
            ],
        ];
    }

    public function userAgent(string $userAgent): self
    {
        Arr::set($this->lighthouseConfig, 'settings.emulatedUserAgent', $userAgent);

        return $this;
    }

    public function headers(array $headers): self
    {
        Arr::set($this->lighthouseConfig, 'settings.extraHeaders', $headers);

        return $this;
    }

    public function budgets(array $budgets): self
    {
        Arr::set($this->lighthouseConfig, 'settings.budgets', $budgets);

        return $this;
    }

    public function categories(Category|string|array ...$categories): self
    {
        if (is_array($categories[0])) {
            $categories = $categories[0];
        }

        $categories = array_map(function (Category|string $category) {
            $category = is_string($category)
                ? Category::fromString($category)
                : $category;

            return $category->value;
        }, $categories);

        Arr::set($this->lighthouseConfig, 'settings.onlyCategories', $categories);

        return $this;
    }

    public function onlyAudits(array|string ...$auditNames): self
    {
        if (is_array($auditNames[0])) {
            $auditNames = $auditNames[0];
        }

        Arr::set($this->lighthouseConfig, 'settings.onlyAudits', $auditNames);
        Arr::forget($this->lighthouseConfig, 'settings.onlyCategories');

        return $this;
    }

    public function skipAudits(array|string ...$auditNames): self
    {
        if (is_array($auditNames[0])) {
            $auditNames = $auditNames[0];
        }

        Arr::set($this->lighthouseConfig, 'settings.skipAudits', $auditNames);

        return $this;
    }

    public function formFactor(string|FormFactor $formFactor): self
    {
        if (is_string($formFactor)) {
            $formFactor = FormFactor::fromString($formFactor);
        }

        Arr::set($this->lighthouseConfig, 'settings.formFactor', $formFactor->value);

        return $this;
    }

    public function screenEmulation(?bool $mobile = false, ?bool $disabled = false, ?int $width = null, ?int $height = null, ?int $deviceScaleRatio = null): self
    {
        $screenEmulation = array_filter(compact('mobile', 'disabled', 'width', 'height', 'deviceScaleRatio'), function ($value) {
            return $value !== null;
        });
        Arr::set($this->lighthouseConfig, 'settings.screenEmulation', $screenEmulation);

        return $this;
    }

    public function throttleNetwork(): self
    {
        Arr::set($this->lighthouseConfig, 'settings.disableNetworkThrottling', false);

        return $this;
    }

    public function doNotThrottleNetwork(): self
    {
        Arr::set($this->lighthouseConfig, 'settings.disableNetworkThrottling', true);

        return $this;
    }

    public function throttleCpu(?int $multiplier = null): self
    {
        Arr::set($this->lighthouseConfig, 'settings.disableCpuThrottling', false);
        Arr::set($this->lighthouseConfig, 'settings.throttlingMethod', 'simulate');

        if (! is_null($multiplier)) {
            Arr::set($this->lighthouseConfig, 'settings.throttling.cpuSlowdownMultiplier', $multiplier);
        }

        return $this;
    }

    public function doNotThrottleCpu(): self
    {
        Arr::set($this->lighthouseConfig, 'settings.disableCpuThrottling', true);

        return $this;
    }

    public function withConfig(array $lighthouseConfig): self
    {
        $this->lighthouseConfig = $lighthouseConfig;

        return $this;
    }

    public function withChromeOptions(array $chromeOptions): self
    {
        $this->chromeOptions = $chromeOptions;

        return $this;
    }

    public function saveHar(bool $saveHar = true): self
    {
        Arr::set($this->lighthouseConfig, 'settings.gatherMode', $saveHar);

        return $this;
    }

    public function timeoutInSeconds(int $timeout): self
    {
        $this->timeoutInSeconds = $timeout;

        return $this;
    }

    public function run(): LighthouseResult
    {
        $arguments = $this->lighthouseScriptArguments();

        $command = [
            (new ExecutableFinder)->find('node', 'node', [
                '/usr/local/bin',
                '/opt/homebrew/bin',
            ]),
            'lighthouse.js',
            json_encode(array_values($arguments)),
        ];

        $process = new Process(
            command: $command,
            cwd: __DIR__.'/../bin',
            timeout: $this->timeoutInSeconds,
        );

        $process->run();

        $result = json_decode($process->getOutput(), true);

        if (! is_array($result)) {
            throw CouldNotRunLighthouse::make($process->getErrorOutput());
        }

        if (array_key_exists('runtimeError', $result['lhr'])) {
            throw LighthouseReportedError::make(
                $result['lhr']['runtimeError']['message'],
                $result['lhr']['runtimeError']['code'],
            );
        }

        $result['report'][0] = json_decode($result['report'][0], true);

        return new LighthouseResult($result);
    }

    public function lighthouseScriptArguments(?string $key = null): mixed
    {
        return Arr::get([
            'url' => $this->url,
            'chromeOptions' => $this->chromeOptions,
            'lighthouseConfig' => $this->lighthouseConfig,
            'timeout' => ($this->timeoutInSeconds * 1000) - 700,
        ], $key);
    }
}
