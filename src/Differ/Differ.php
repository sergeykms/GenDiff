<?php

namespace App\Differ;

use function App\Parser\parse;

function render(array $diff): string
{
    $format = '  %s %s: %s';
    $result = "";
    foreach ($diff as $items) {
        $result .= sprintf($format, $items["mark"], $items["key"], (string)$items["value"]) . "\n";
    }

    return "{\n" . $result . "}";
}

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

function setMessage(string $key, mixed $beforeValue, mixed $afterValue, string $mark): array
{
    return [
        'key' => $key,
        'mark' => $mark,
        'beforeValue' => gettype($beforeValue) != 'array' ? getValue($beforeValue) : $beforeValue,
        'afterValue' => gettype($afterValue) != 'array' ? getValue($afterValue) : $afterValue,
        'typeBefore' => gettype($beforeValue),
        'typeAfter' => gettype($afterValue),

    ];
}

function genDiff(string $pathToFile1, string $pathToFile2,): void
{
    $file1 = parse($pathToFile1);
    $file2 = parse($pathToFile2);
    $allDiffer = getDiff($file1, $file2);
    print_r($allDiffer);
//    return render($allDiffer);
}

function getDiff(array $file1, array $file2): array
{
    $filesKeys = array_merge(array_keys($file1), array_keys($file2));
    $uniqueFilesKeys = (array_unique($filesKeys));
    sort($uniqueFilesKeys);
    return array_map(function ($items) use ($file1, $file2) {
        if (key_exists($items, $file1) && key_exists($items, $file2)) {
            if (is_array($file1[$items]) && is_array($file2[$items])) {
               return [$items => getDiff($file1[$items], $file2[$items])];
            } elseif ($file1[$items] === $file2[$items]) {
                $node =  setMessage($items, $file1[$items], $file2[$items], '=');
            } else {
                $node = setMessage($items, $file1[$items], $file2[$items], '<>');
            }
        }
        if (key_exists($items, $file1) && !key_exists($items, $file2)) {
            $node = setMessage($items, $file1[$items], null, '>');
        } else {
            $node = setMessage($items, null, $file2[$items], '<');
        }
        return $node;
    }, $uniqueFilesKeys);
}
