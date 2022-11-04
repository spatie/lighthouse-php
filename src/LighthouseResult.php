<?php

namespace Spatie\Lighthouse;

use Illuminate\Support\Arr;
use Spatie\Lighthouse\Enums\Category;

class LighthouseResult
{
    public function __construct(public array $rawResults)
    {
        $this->rawResults['report'][0] = json_decode($this->rawResults['report'][0], true);
    }

    public function scores(): array
    {
        $scores = [];

        foreach (Category::values() as $category) {
            $scores[$category] = intval($this->get("categories.$category.score") * 100);
        }

        return array_filter($scores);
    }

    public function json(string $path = null): mixed
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

    public function get(string $path): mixed
    {
        return $this->json($path);
    }

    public function rawResults(): array
    {
        return $this->rawResults;
    }
}
