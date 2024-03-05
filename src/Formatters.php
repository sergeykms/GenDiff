<?php

namespace App\Formatters;

use function App\Formatters\Stylish\stylish;
use function App\Formatters\Plain\plain;
use function App\Formatters\Json\json;

function formatters(array $allDiffer, string $format): string
{
    return match ($format) {
        'stylish' => "{\n" . stylish($allDiffer) . "}",
        'plain' => plain($allDiffer),
        'json' => json($allDiffer),
        default => 'unknown',
    };
}
