<?php

namespace App\Differ;

use function App\Parser\parse;
use function App\Stylish\renderDiff;

function getValue(mixed $value): string
{
    return match (gettype($value)) {
        'boolean' => $value ? 'true' : 'false',
        'NULL' => 'null',
        default => $value,
    };
}

function parseFile(string $pathToFile): mixed
{
    $content = (string)file_get_contents($pathToFile);
    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
}

function setMessage(string $key, mixed $before, mixed $after, string $type): array
{
    return [
        'key' => $key,
        'type' => $type,
        'before' => gettype($before) != 'array' ? getValue($before) : $before,
        'after' => gettype($after) != 'array' ? getValue($after) : $after,
    ];
}

function genDiff(string $pathToFile1, string $pathToFile2, string $format): string
{
    $file1 = parse($pathToFile1);
    $file2 = parse($pathToFile2);
    $allDiffer = getDiff($file1, $file2);
    return renderDiff($allDiffer, $format);
//    print_r($allDiffer);
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
    sort($uniqueFilesKeys);
    return array_reduce($uniqueFilesKeys, function ($acc, $items) use ($file1, $file2) {
        if (key_exists($items, $file1) && key_exists($items, $file2)) {
            if (is_array($file1[$items]) && is_array($file2[$items])) {
                $acc[] = setMessage($items, getDiff($file1[$items], $file2[$items]), null, 'unchanged');
            } elseif ($file1[$items] === $file2[$items]) {
                $acc[] = setMessage($items, $file1[$items], $file1[$items], 'unchanged');
            } else {
                $deletedItems = setMessage($items, isArray($file1[$items]), null, 'deleted');
                $addedItems = setMessage($items, null, isArray($file2[$items]), 'added');
                $acc[] = setMessage($items,
                    $deletedItems,
                    $addedItems, 'changed');
            }
        } elseif (key_exists($items, $file1) && !key_exists($items, $file2)) {
            $acc[] = setMessage($items, isArray($file1[$items]), null, 'deleted');
        } else {
            $acc[] = setMessage($items, null, isArray($file2[$items]), 'added');
        }
        return $acc;
    }, []);
}
