<?php

namespace App\Formatters;

use function App\Formatters\Stylish\stylish;

function formatters(array $allDiffer, string $format): string
{
    return match ($format) {
        'stylish' => "{\n" . stylish($allDiffer) . "}",
        default => "{\n" . stylish($allDiffer) . "}",
    };
}
