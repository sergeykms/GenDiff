<?php

namespace App\Stylish;

function renderDiff(array $diff): string
{
    $format = '  %s %s: %s';
    $result = "";
    foreach ($diff as $items) {
        switch ($items["mark"]) {
            case 'unchanged':
                $result .= sprintf($format, " ", $items["key"], (string)$items["beforeValue"]) . "\n";
                break;
            case 'changed':
                $result .= sprintf($format, "-", $items["key"], (string)$items["beforeValue"])
                    . "\n" . sprintf($format, "+", $items["key"], (string)$items["afterValue"]) . "\n";
                break;
            case 'deleted':
                $result .= sprintf($format, "-", $items["key"], (string)$items["beforeValue"]) . "\n";
                break;
            case 'added':
                $result .= sprintf($format, "+", $items["key"], (string)$items["afterValue"]) . "\n";
                break;
        }
    }
    return "{\n" . $result . "}";
}

function stylish(array $allDiffer): void
{
    array_map(function ($items) {
        if (is_array($items) && key_exists('mark', $items) ) {
            renderDiff($items);
        }
    }, $allDiffer);
//    return $result;
}

