<?php

namespace App\Stylish;

function stylish(array $diff, int $level = 0): string
{
    return array_reduce($diff, function ($acc, $items) use ($level) {
        $format = '%s %s: %s';
        $level++;
        $indent = $level * 4 - 2;
        if (is_array($items['value'])) {
            $acc .= str_repeat(" ", $indent) . sprintf($format, $items["type"], $items["key"], "")
                . "{\n" . stylish($items['value'], $level)
                . str_repeat(" ", $indent) . "  }\n";
        } else {
            $acc .= str_repeat(" ", $indent)
                . rtrim(sprintf($format, $items["type"], $items["key"], $items["value"]))
                . "\n";
        }
        return $acc;
    }, '');
}

function renderDiff(array $allDiffer, string $format): string
{
    return match ($format) {
        'stylish' => "{\n" . stylish($allDiffer) . "}",
        default => "{\n" . stylish($allDiffer) . "}",
    };
}
