<?php

namespace App\Stylish;

use function App\Differ\getValue;

function getItems(string $type, string $key, string $value): string
{
    $format = '%s %s: %s';
    $items = "";
    switch ($type) {
        case 'unchanged':
            $items = rtrim(sprintf($format, " ", $key, $value));
            break;
        case 'deleted':
            $items = rtrim(sprintf($format, "-", $key, $value));
            break;
        case 'added':
            $items = rtrim(sprintf($format, "+", $key, $value));
            break;
    }
    return $items;
}

function getDiffMessage(int $level, string $type, string $key, mixed $value): string
{
    $indent = $level * 4 - 2;
    $result = "";
    if (is_array($value)) {
        $result = str_repeat(" ", $indent) . getItems($type, $key, "")
            . " {\n" . stylish($value, $level)
            . str_repeat(" ", $indent) . "  }\n";
    } else {
        $result = str_repeat(" ", $indent)
            . getItems($type, $key, $value)
            . "\n";
    }
    return $result;
}

function stylish(array $diff, int $level = 0): string
{
    return array_reduce($diff, function ($acc, $items) use ($level) {
        $level++;
        $indent = $level * 4 - 2;
        if ($items["type"] != 'changed') {
            ($items["type"] === 'unchanged' || $items["type"] === 'deleted')
                ?  $value =  $items['before']
                : $value =  $items['after'];
            $acc .= getDiffMessage($level, $items["type"], $items["key"], $value);
        } else {
            $deletedItems = $items["before"];
            $addedItems = $items["after"];
            $acc .= getDiffMessage($level, $deletedItems["type"], $deletedItems["key"], $deletedItems["before"]);
            $acc .= getDiffMessage($level, $addedItems["type"], $addedItems["key"], $addedItems["after"]);
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
