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
                'emulatedFormFactor' => 'desktop',
                'output' => ['json', 'html'],
                'disableNetworkThrottling' => true,
                'disableCpuThrottling' => true,
                'throttlingMethod' => 'provided',
            ],
        ];
    }

    public function defaultChromeOptions(): array
    {
        return [
            'chromeFlags' => [
                '--headless',
                '--no-sandbox',
            ],
        ];
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

        $this->lighthouseConfig['settings']['onlyCategories'] = $categories;

        return $this;
    }

    public function formFactor(string|FormFactor $formFactor): self
    {
        if (is_string($formFactor)) {
            $formFactor = FormFactor::fromString($formFactor);
        }

        $this->lighthouseConfig['settings']['emulatedFormFactor'] = $formFactor->value;
        
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

    public function run(): LighthouseResult
    {
        $arguments = $this->lighthouseScriptArguments();

        $command = [
            (new ExecutableFinder())->find('node', 'node', [
                '/usr/local/bin',
                '/opt/homebrew/bin',
            ]),
            'lighthouse.js',
            json_encode(array_values($arguments)),
        ];

        $process = new Process(
            command: $command,
            cwd: __DIR__ . '/../bin',
            timeout: null,
        );

        $process->run();

        $result = json_decode($process->getOutput(), true);

        if (! is_array($result)) {
            throw CouldNotRunLighthouse::make($process->getErrorOutput());
        }

        if (array_key_exists('runtimeError', $result)) {
            throw LighthouseReportedError::make(
                $result['runtimeError']['message'],
                $result['runtimeError']['code'],
            );
        }

        $result['report'][0] = json_decode($result['report'][0], true);

        return new LighthouseResult($result);
    }

    public function lighthouseScriptArguments(string $key = null): mixed
    {
        return Arr::get([
            'url' => $this->url,
            'chromeOptions' => $this->chromeOptions,
            'lighthouseConfig' => $this->lighthouseConfig,
        ], $key);
    }
}
