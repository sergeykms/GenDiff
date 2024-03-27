<?php

namespace Formatters\Stylish;

const STEP_INDENT = 4;

function renderArray(array $array, int $level): string
{
    $keys = array_keys($array);
    $viewArray = array_map(function ($key) use ($array, $level,) {
        $indentBefore = str_repeat(' ', ($level + 1) * STEP_INDENT - 2);
        $indentAfter = str_repeat(' ', ($level + 1) * STEP_INDENT);
        if (is_array($array[$key])) {
            $format = "\n%s %s %s: {%s\n%s}";
            $value = sprintf($format, $indentBefore, '', $key, getValue($array[$key], ($level + 1)), $indentAfter);
        } else {
            $format = "\n%s %s %s: %s";
            $value = sprintf($format, $indentBefore, '', $key, getValue($array[$key], ($level + 1)));
        }
        return "{$value}";
    }, $keys);
    return implode('', $viewArray);
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
    $indentBefore = str_repeat(' ', $level * STEP_INDENT - 2);
    $indentAfter = str_repeat(' ', $level * STEP_INDENT);
    if (is_array($value)) {
        $format = '%s %s: %s{';
        $message = sprintf($format, $mark, $key, '');
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
    $format = '%s%s: {%s';
    $indent = str_repeat(' ', $level * STEP_INDENT - 4);
    if (is_array($value)) {
        $message = sprintf($format, '', $key, '');
        $message2 = renderArray($value, $level);
        return "\n{$indent}{$message}{$message2}";
    } else {
        $message = sprintf($format, "", $key, rtrim(getValue($value)));
        return "\n{$indent}{$message}\n{$indent}}";
    }
}

function stylish(array $diff, int $level = 1): string
{
    return implode('', array_map(function ($items) use ($level) {
        return match ($items['type']) {
            'node' => renderNode($level + 1, $items['key'], stylish($items['children'], $level + 1), ' '),
            'unchanged' => renderItem($level, $items['key'], $items['$values1'], ' '),
            'deleted' => renderItem($level, $items['key'], $items['$values1'], '-'),
            'added' => renderItem($level, $items['key'], $items['$values2'], '+'),
            'changed' => implode('', [renderItem($level, $items['key'], $items['$values1'], '-'),
                renderItem($level, $items["key"], $items['$values2'], '+')]),
            default => '',
        };
    }, $diff));
}

function getStylish(array $diff): string
{
    return "{" . stylish($diff) . "\n}";
}
