<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parseJson(string $pathToFile): mixed
{
    $content = (string)file_get_contents($pathToFile);
    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
}

function parseYaml(string $pathToFile): mixed
{
    return Yaml::parseFile($pathToFile);
}

function parse(string $pathToFile): array
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    return match ($extension) {
        "json" => parseJson($pathToFile),
        'yaml', 'yml' => parseYaml($pathToFile),
        default => throw new \Exception("Format {$extension} not supported."),
    };
}
