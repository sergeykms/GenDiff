<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Formatters\formatters;

//function getValue(mixed $value): mixed
//{
////    return match (gettype($value)) {
////        'boolean' => $value ? 'true' : 'false',
////        'NULL' => 'null',
////        'int' => (int) $value,
////        default => $value,
////    };
//
//    return $value;
//}

function parseFile(string $pathToFile): mixed
{
    $content = (string)file_get_contents($pathToFile);
    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
}

function createNode(string $type, string $key, mixed $before, mixed $after, mixed $children = null): array
{
    $diff = [
        'type' => $type,
        'key' => $key,
        'before' => $before,
        'after' => $after,
    ];
    if ($children != null) {
        $diff["children"] = $children;
    }
    return $diff;
}

function genDiff(string $pathToFile1, string $pathToFile2, string $format = "stylish"): string
{
    $file1 = parse($pathToFile1);
    $file2 = parse($pathToFile2);
    $allDiffer = getDiff($file1, $file2);
    return formatters($allDiffer, $format);
}

function isArray(mixed $items): mixed
{
    if (is_array($items)) {
        return getDiff($items, $items);
    } else {
        return $items;
    }
}

function getDiff(array $file1, array $file2): array
{
    $filesKeys = array_merge(array_keys($file1), array_keys($file2));
    $uniqueFilesKeys = (array_unique($filesKeys));
    asort($uniqueFilesKeys);
    return array_map(function ($items) use ($file1, $file2) {
        if (key_exists($items, $file1) && key_exists($items, $file2)) {
            if (is_array($file1[$items]) && is_array($file2[$items])) {
                $node = createNode('node', $items, null, null, getDiff($file1[$items], $file2[$items]));
            } elseif ($file1[$items] === $file2[$items]) {
                $node = createNode('unchanged', $items, $file1[$items], $file2[$items]);
            } else {
                $node = createNode('changed', $items, $file1[$items], $file2[$items]);
            }
        } elseif (key_exists($items, $file1) && !key_exists($items, $file2)) {
            $node = createNode('deleted', $items, $file1[$items], null);
        } else {
            $node = createNode('added', $items, null, $file2[$items]);
        }
        return $node;
    }, $uniqueFilesKeys);
}
