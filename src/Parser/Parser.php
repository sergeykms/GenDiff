<?php

namespace Parser\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(mixed $content, string $type): array
{
    return match ($type) {
        'json' => json_decode($content, true, 512, JSON_THROW_ON_ERROR),
        'yaml' => Yaml::parse($content),
        default => throw new \Exception('Format {$type} not supported.'),
    };
}
