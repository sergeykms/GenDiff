<?php

namespace Differ\Differ;

use function Parser\Parser\parse;
use function Formatters\formatMessage;
use function Functional\sort;

function createNode(string $type, string $key, array $values, mixed $children = null): array
{
    return  [
        'type' => $type,
        'key' => $key,
        '$values1' => $values[0],
        '$values2' => $values[1],
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
    $dataForComparison1 = parse($content1, $type1);
    $dataForComparison2 = parse($content2, $type2);
    $allDiffer = getDiff($dataForComparison1, $dataForComparison2);
    return formatMessage($allDiffer, $format);
}

function getDiff(array $data1, array $data2): array
{
    $filesKeys = array_merge(array_keys($data1), array_keys($data2));
    $uniqueFilesKeys = (array_unique($filesKeys));
    $sortedUniqueFilesKeys = sort($uniqueFilesKeys, fn ($left, $right) => strcmp($left, $right));
    return array_map(function ($items) use ($data1, $data2) {
        if (array_key_exists($items, $data1) && array_key_exists($items, $data2)) {
            if (is_array($data1[$items]) && is_array($data2[$items])) {
                $node = createNode('node', $items, [null, null], getDiff($data1[$items], $data2[$items]));
            } elseif ($data1[$items] === $data2[$items]) {
                $node = createNode('unchanged', $items, [$data1[$items], $data2[$items]]);
            } else {
                $node = createNode('changed', $items, [$data1[$items], $data2[$items]]);
            }
        } elseif (array_key_exists($items, $data1) && !array_key_exists($items, $data2)) {
            $node = createNode('deleted', $items, [$data1[$items], null]);
        } else {
            $node = createNode('added', $items, [null, $data2[$items]]);
        }
        return $node;
    }, $sortedUniqueFilesKeys);
}
