<?php

namespace Formatters\Plain;

use function Functional\concat;

function getValue(mixed $value): mixed
{
    if (is_array($value)) {
        return "[complex value]";
    } else {
        return match (gettype($value)) {
            'boolean' => $value ? 'true' : 'false',
            'NULL' => 'null',
            'integer' => $value,
            default => "'" . $value . "'",
        };
    }
}

function plain(array $diff, string $level = null): string
{
    $message = array_map(function ($items) use ($level) {
        $temp = $level != null ? concat($level, '.', $items["key"]) : $items["key"];
        switch ($items["type"]) {
            case 'node':
                return plain($items['children'], $temp);
            case 'deleted':
                $format = "Property '%s' was removed\n";
                return sprintf($format, $temp);
            case 'added':
                $format = "Property '%s' was added with value: %s\n";
                return sprintf($format, $temp, getValue($items['after']));
            case 'changed':
                $format = "Property '%s' was updated. From %s to %s\n";
                return sprintf($format, $temp, getValue($items['before']), getValue($items['after']));
        }
    }, $diff);
    return implode("", $message);
}

function getPlain(array $diff): string
{
    return plain($diff);
}
