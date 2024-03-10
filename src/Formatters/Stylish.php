<?php

namespace Formatters\Stylish;

const STEP_INDENT = 4;

function renderArray(array $array, int $level): string
{
    $keys = array_keys($array);
    $viewArray = array_map(function ($key) use ($array, $level,) {
        $level++;
        $indentBefore = str_repeat(" ", $level * STEP_INDENT - 2);
        $indentAfter = str_repeat(" ", $level * STEP_INDENT);
        is_array($array[$key]) ? $format = "\n%s %s %s: {%s\n%s}" : $format = "\n%s %s %s: %s";
        $value = sprintf($format, $indentBefore, "", $key, getValue($array[$key], $level), $indentAfter);
        return "{$value}";
    }, $keys);
    return implode("", $viewArray);
}

function getValue(mixed $value, int $level = 0): mixed
{
    return match (gettype($value)) {
        'boolean' => $value ? 'true' : 'false',
        'NULL' => 'null',
        'array' => renderArray($value, $level),
        default => $value,
    };
}

function renderItem(int $level, string $key, mixed $value, string $mark): string
{
    $indentBefore = str_repeat(" ", $level * STEP_INDENT - 2);
    $indentAfter = str_repeat(" ", $level * STEP_INDENT);
    if (is_array($value)) {
        $format = '%s %s: %s{';
        $message = sprintf($format, $mark, $key, "");
        $message2 = renderArray($value, $level);
        return "\n{$indentBefore}{$message}{$message2}\n{$indentAfter}}";
    } else {
        $format = '%s %s: %s';
        $message = sprintf($format, $mark, $key, getValue($value));
        return "\n{$indentBefore}{$message}";
    }
}

function renderNode(int $level, string $key, mixed $value, string $mark): string
{
    $format = "%s%s: {%s";
    $indent = str_repeat(" ", $level * STEP_INDENT - 4);
    if (is_array($value)) {
        $message = sprintf($format, "", $key, "");
        $message2 = renderArray($value, $level);
        return "\n{$indent}{$message}{$message2}";
    } else {
        $message = sprintf($format, "", $key, rtrim(getValue($value)));
        return "\n{$indent}{$message}\n{$indent}}";
    }
}

function stylish(array $diff, int $level = 1): string
{
    $message = array_map(function ($items) use ($level) {
//        $value = "";
        switch ($items["type"]) {
            case 'node':
                $level++;
                return renderNode($level, $items["key"], stylish($items['children'], $level), " ");
//                break;
            case 'unchanged':
                return renderItem($level, $items["key"], $items['before'], " ");
//                break;
            case 'deleted':
                return renderItem($level, $items["key"], $items['before'], "-");
//                break;
            case 'added':
                return renderItem($level, $items["key"], $items['after'], "+");
//                break;
            case 'changed':
                return implode("", [renderItem($level, $items["key"], $items['before'], "-"),
                    renderItem($level, $items["key"], $items['after'], "+")]);
//                break;
        }
//        return $value;
    }, $diff);
    return implode("", $message);
}

//function stylish(array $diff, int $level = 1): string
//{
//    $message = array_reduce($diff, function ($acc, $items) use ($level) {
//        switch ($items["type"]) {
//            case 'node':
//                $level++;
//                $acc[] = renderNode($level, $items["key"], stylish($items['children'], $level), " ");
//                break;
//            case 'unchanged':
//                $acc[] = renderItem($level, $items["key"], $items['before'], " ");
//                break;
//            case 'deleted':
//                $acc[] = renderItem($level, $items["key"], $items['before'], "-");
//                break;
//            case 'added':
//                $acc[] = renderItem($level, $items["key"], $items['after'], "+");
//                break;
//            case 'changed':
//                $acc[] = renderItem($level, $items["key"], $items['before'], "-");
//                $acc[] = renderItem($level, $items["key"], $items['after'], "+");
//                break;
//        }
//        return $acc;
//    }, []);
//    return implode("", $message);
//}

function getStylish(array $diff): string
{
    return "{" . stylish($diff) . "\n}";
}
