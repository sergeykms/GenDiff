<?php

namespace App\Formatters\Plain;

function getValue(mixed $value): string
{
    if (is_array($value)) {
        return "[complex value]";
    } else {
        return match ($value) {
            'false' => 'false',
            'true' => 'true',
            'null' => 'null',
            default => "'" . $value . "'",
        };
//        return "'" . $value . "'";
    }
}

function plain(array $diff, string $level = ""): string
{
    return array_reduce($diff, function ($acc, $items) use ($level) {
        $level .= $items["key"] . ".";
        switch ($items["type"]) {
            case 'node':
                $acc .= plain($items['value'], $level);
                break;
            case 'deleted':
                $acc .= "Property " . "'" . substr($level, 0, -1) . "'" . " was removed" . "\n";
                break;
            case 'added':
                $acc .= "Property " . "'" . substr($level, 0, -1) . "'"
                    . " was added with value: " . getValue($items['value']) . "\n";
                break;
            case 'changed':
                $deletedItems = $items["before"];
                $addedItems = $items["after"];
                $acc .= "Property " . "'" . substr($level, 0, -1) . "'" . " was updated. From "
                    . getValue($deletedItems["value"]) . " to " . getValue($addedItems["value"]) . "\n";
                break;
        }
        return $acc;
    }, '');
}
