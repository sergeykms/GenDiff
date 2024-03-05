<?php

namespace App\Formatters\Stylish;

function createDiffMessage(int $level, string $key, mixed $value, string $mark): string
{
    $format = '%s %s: %s';
    $indent = $level * 4 - 2;
    if (is_array($value)) {
        return str_repeat(" ", $indent) . rtrim(sprintf($format, $mark, $key, ""))
            . " {\n" . stylish($value, $level)
            . str_repeat(" ", $indent) . "  }\n";
    } else {
        return str_repeat(" ", $indent)
            . rtrim(sprintf($format, $mark, $key, $value))
            . "\n";
    }
}

function stylish(array $diff, int $level = 0): string
{
    return array_reduce($diff, function ($acc, $items) use ($level) {
        $level++;
        switch ($items["type"]) {
            case 'unchanged':
            case 'node':
                $acc .= createDiffMessage($level, $items["key"], $items['value'], " ");
                break;
            case 'deleted':
                $acc .= createDiffMessage($level, $items["key"], $items['value'], "-");
                break;
            case 'added':
                $acc .= createDiffMessage($level, $items["key"], $items['value'], "+");
                break;
            case 'changed':
                $deletedItems = $items["before"];
                $addedItems = $items["after"];
                $acc .= createDiffMessage($level, $deletedItems["key"], $deletedItems["value"], "-");
                $acc .= createDiffMessage($level, $addedItems["key"], $addedItems["value"], "+");
                break;
        }
        return $acc;
    }, '');
}
