<?php

namespace Formatters;

use function Formatters\Stylish\getStylish;
use function Formatters\Plain\getPlain;
use function Formatters\Json\json;

function formatters(array $allDiffer, string $format): string
{
    return match ($format) {
        'stylish' => getStylish($allDiffer),
        'plain' => getPlain($allDiffer),
        'json' => json($allDiffer),
        default => throw new \Exception('Format {$format} not supported.'),
    };
}
