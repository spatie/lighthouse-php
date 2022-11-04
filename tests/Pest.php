<?php

function getJsonStub(string $stubName): array
{
    $content = file_get_contents(__DIR__."/stubs/{$stubName}.json");

    return json_decode($content, true);
}

function getTemporaryDirectoryPath(): string
{
    return __DIR__.'/temp';
}
