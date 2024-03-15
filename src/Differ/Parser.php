<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(mixed $pathToFile): array
{
    $content = (string) file_get_contents($pathToFile);
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    return match ($extension) {
        "json" => json_decode($content, true, 512, JSON_THROW_ON_ERROR),
        'yaml', 'yml' => Yaml::parseFile($pathToFile),
        default => throw new \Exception("Format {$extension} not supported."),
    };
}
