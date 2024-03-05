<?php

namespace App\Formatters;

use function App\Formatters\Stylish\stylish;
use function App\Formatters\Plain\plain;

function formatters(array $allDiffer, string $format): string
{
    return match ($format) {
        'stylish' => "{\n" . stylish($allDiffer) . "}",
        'plain' => plain($allDiffer),
        default => 'unknown',
    };
}
