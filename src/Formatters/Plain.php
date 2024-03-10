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
    $message = array_reduce($diff, function ($acc, $items) use ($level) {
        $level[] = $items["key"];
        switch ($items["type"]) {
            case 'node':
                $acc[] = plain($items['children'], $level);
                break;
            case 'deleted':
                $format = "Property '%s' was removed\n";
                $acc[] = sprintf($format, implode(".", $level));
                break;
            case 'added':
                $format = "Property '%s' was added with value: %s\n";
                $acc[] = sprintf($format, implode(".", $level), getValue($items['after']));
                break;
            case 'changed':
                $format = "Property '%s' was updated. From %s to %s\n";
                $acc[] = sprintf($format, implode(".", $level), getValue($items['before']), getValue($items['after']));
                break;
        }
        return $acc;
    }, []);
    return implode("", $message);
}
function getPlain(array $diff): string
{
    return plain($diff);
}
