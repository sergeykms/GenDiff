<?php

namespace Formatters\Plain;

use function Functional\concat;

function getValue(mixed $value): mixed
{
    if (is_array($value)) {
        return '[complex value]';
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
    return implode('', array_map(function ($items) use ($level) {
        $path = $level != null ? concat($level, '.', $items['key']) : $items['key'];
        return match ($items['type']) {
            'node' => plain($items['children'], $path),
            'deleted' => sprintf("Property '%s' was removed\n", $path),
            'added' => sprintf("Property '%s' was added with value: %s\n", $path, getValue($items['after'])),
            'changed' => sprintf("Property '%s' was updated. From %s to %s\n", $path,
                getValue($items['before']), getValue($items['after'])),
            default => '',
        };
    }, $diff));
}

function getPlain(array $diff): string
{
    return plain($diff);
}
