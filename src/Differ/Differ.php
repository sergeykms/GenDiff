<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Formatters\formatters;
use function Functional\sort;

function parseFile(string $pathToFile): mixed
{
    $content = (string)file_get_contents($pathToFile);
    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
}

function createNode(string $type, string $key, array $values, mixed $children = null): array
{
    return  [
        'type' => $type,
        'key' => $key,
        'before' => $values[0],
        'after' => $values[1],
        'children' => $children,
    ];
}

function getContent(string $pathToFile): array
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    $content = (string) file_get_contents($pathToFile);
    return match ($extension) {
        'json' => [
            'content' => $content,
            'type' => 'json'
        ],
        'yaml', 'yml' => [
            'content' => $content,
            'type' => 'yaml'
        ],
        default => throw new \Exception('Format {$extension} not supported.'),
    };
}

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    ['content' => $content1, 'type' => $type1] = getContent((string) realpath($pathToFile1));
    ['content' => $content2, 'type' => $type2] = getContent((string) realpath($pathToFile2));
    $file1 = parse($content1, $type1);
    $file2 = parse($content2, $type2);
    $allDiffer = getDiff($file1, $file2);
    return formatters($allDiffer, $format);
}

function getDiff(array $file1, array $file2): array
{
    $filesKeys = array_merge(array_keys($file1), array_keys($file2));
    $uniqueFilesKeys = (array_unique($filesKeys));
    $sortedUniqueFilesKeys = sort($uniqueFilesKeys, fn ($left, $right) => strcmp($left, $right));
    return array_map(function ($items) use ($file1, $file2) {
        if (key_exists($items, $file1) && key_exists($items, $file2)) {
            if (is_array($file1[$items]) && is_array($file2[$items])) {
                $node = createNode('node', $items, [null, null], getDiff($file1[$items], $file2[$items]));
            } elseif ($file1[$items] === $file2[$items]) {
                $node = createNode('unchanged', $items, [$file1[$items], $file2[$items]]);
            } else {
                $node = createNode('changed', $items, [$file1[$items], $file2[$items]]);
            }
        } elseif (key_exists($items, $file1) && !key_exists($items, $file2)) {
            $node = createNode('deleted', $items, [$file1[$items], null]);
        } else {
            $node = createNode('added', $items, [null, $file2[$items]]);
        }
        return $node;
    }, $sortedUniqueFilesKeys);
}
