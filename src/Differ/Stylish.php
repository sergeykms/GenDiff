<?php

namespace App\Stylish;

use function App\Differ\getValue;

function getItems(string $type, string $key, string $value1, string $value2): string
{
    $format = '%s %s: %s';
    $items = "";
    switch ($type) {
        case 'unchanged':
            $items = rtrim(sprintf($format, " ", $key, $value1));
            break;
//        case 'changed':
//            $items = rtrim(sprintf($format, " ", $key, $value)) . "\n" . rtrim(sprintf($format, " ", $key, $value));
//            break;
        case 'deleted':
            $items = rtrim(sprintf($format, "-", $key, $value1));
            break;
        case 'added':
            $items = rtrim(sprintf($format, "+", $key, $value2));
            break;
    }
    return $items;
}

function stylish(array $diff, int $level = 0): string
{
    return array_reduce($diff, function ($acc, $items) use ($level) {
        $format = '%s %s: %s';
        $level++;
        $indent = $level * 4 - 2;

        if($items["type"] != 'changed') {
            ($items["type"] === 'unchanged' || $items["type"] === 'deleted') ?  $value =  $items['value1'] : $value =  $items['value2'];

            if (is_array($value)) {
                $acc .= str_repeat(" ", $indent) . getItems($items["type"], $items["key"], "", "")
//                sprintf($format, $items["type"], $items["key"], "")
                    . "{\n" . stylish($value, $level)
                    . str_repeat(" ", $indent) . "  }\n";
            } else {
                $acc .= str_repeat(" ", $indent)
                    . getItems($items["type"], $items["key"], $items["value1"], $items["value2"])
                    . "\n";
            }
        } else {
            $acc .= str_repeat(" ", $indent)
                . getItems("delete", $items["key"], $items["value1"], $items["value2"])
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
