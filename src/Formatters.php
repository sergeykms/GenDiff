<?php

namespace Formatters;

use function Formatters\Stylish\stylish;
use function Formatters\Plain\plain;
use function Formatters\Json\json;

function formatters(array $allDiffer, string $format): string

{
    return match ($format) {
        'stylish' => "{" . stylish($allDiffer) . "\n}",
        'plain' => plain($allDiffer),
        'json' => json($allDiffer),
        default => throw new \Exception("Format {$format} not supported."),
    };
}
