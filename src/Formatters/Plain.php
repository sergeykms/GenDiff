<?php

namespace Formatters\Plain;

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

function plain(array $diff, array $level = []): string
{
    $message = array_map(function ($items) use ($level) {
        $level[] = $items["key"];
        switch ($items["type"]) {
            case 'node':
                return plain($items['children'], $level);
            case 'deleted':
                $format = "Property '%s' was removed\n";
                return sprintf($format, implode(".", $level));
            case 'added':
                $format = "Property '%s' was added with value: %s\n";
                return sprintf($format, implode(".", $level), getValue($items['after']));
            case 'changed':
                $format = "Property '%s' was updated. From %s to %s\n";
                return sprintf($format, implode(".", $level), getValue($items['before']), getValue($items['after']));
        }
    }, $diff);
    return implode("", $message);
}

function getPlain(array $diff): string
{
    return plain($diff);
}
