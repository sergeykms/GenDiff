<?php

namespace App\Stylish;

function renderDiff(array $diff, int $level = 0): string
{
    return array_reduce($diff, function ($acc, $items) use ($level) {
        $format = '%s %s: %s';
        $level++;
        $indent = $level * 4 - 2;
        if (is_array($items['value'])) {
            $acc .= str_repeat(" ", $indent) . sprintf($format, $items["type"], $items["key"], "")
                . " {\n" . renderDiff($items['value'], $level)
                . str_repeat(" ", $indent) . "  }\n";
        } else {
            $acc .= str_repeat(" ", $indent) . sprintf($format, $items["type"], $items["key"], $items["value"]) . "\n";
        }
        return $acc;
    }, '');
}

function stylish(array $allDiffer): string
{
    return "{\n" . renderDiff($allDiffer) . "}";
}
