<?php

namespace App\Differ;

use function App\Parser\parse;
use function App\Stylish\stylish;

//function render(array $diff): string
//{
//    $format = '  %s %s: %s';
//    $result = "";
//    foreach ($diff as $items) {
//        switch ($items["mark"]) {
//            case 'unchanged':
//                $result .= sprintf($format, " ", $items["key"], (string)$items["beforeValue"]) . "\n";
//                break;
//            case 'changed':
//                $result .= sprintf($format, "-", $items["key"], (string)$items["beforeValue"])
//                    . "\n" . sprintf($format, "+", $items["key"], (string)$items["afterValue"]) . "\n";
//                break;
//            case 'deleted':
//                $result .= sprintf($format, "-", $items["key"], (string)$items["beforeValue"]) . "\n";
//                break;
//            case 'added':
//                $result .= sprintf($format, "+", $items["key"], (string)$items["afterValue"]) . "\n";
//                break;
//        }
//    }
//    return "{\n" . $result . "}";
//}

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

function setMessage(string $key, mixed $value, string $mark): array
{
    return [
        'key' => $key,
        'mark' => $mark,
        'value' => gettype($value) != 'array' ? getValue($value) : $value,
//        'afterValue' => gettype($afterValue) != 'array' ? getValue($afterValue) : $afterValue,
//        'typeBefore' => gettype($beforeValue),
//        'typeAfter' => gettype($afterValue),

    ];
}

function genDiff(string $pathToFile1, string $pathToFile2): void
{
    $file1 = parse($pathToFile1);
    $file2 = parse($pathToFile2);
    $allDiffer = getDiff($file1, $file2);
//    echo "\n";
//    print_r($file1);
//    echo "\n\n\n";
//    print_r($file2);
//    echo "\n\n\n ===================================================================";
    print_r($allDiffer);
//    return stylish($allDiffer);
//    stylish($allDiffer);

}

//function getDiff(array $file1, array $file2): array
//{
//    $filesKeys = array_merge(array_keys($file1), array_keys($file2));
//    $uniqueFilesKeys = (array_unique($filesKeys));
//    sort($uniqueFilesKeys);
//    return array_map(function ($items) use ($file1, $file2) {
//        if (key_exists($items, $file1) && key_exists($items, $file2)) {
//            if (is_array($file1[$items]) && is_array($file2[$items])) {
//                $node = setMessage($items, getDiff($file1[$items], $file2[$items]), 'array');
////                return [$items => ['children' => getDiff($file1[$items], $file2[$items])]];
//            } elseif ($file1[$items] === $file2[$items]) {
//                $node = setMessage($items, $file1[$items], 'unchanged');
//            } else {
//                $node = array_merge([setMessage($items, $file1[$items], 'deleted'), setMessage($items, $file2[$items], 'added')]) ;
//            }
//        } elseif (key_exists($items, $file1) && !key_exists($items, $file2)) {
//            $node = setMessage($items, $file1[$items], 'deleted');
//        } else {
//            $node = setMessage($items, $file2[$items], 'added');
//        }
//        return $node;
//    }, $uniqueFilesKeys);
//}


function getDiff(array $file1, array $file2): array
{
    $filesKeys = array_merge(array_keys($file1), array_keys($file2));
    $uniqueFilesKeys = (array_unique($filesKeys));
    sort($uniqueFilesKeys);
    return array_reduce($uniqueFilesKeys, function ($acc, $items) use ($file1, $file2) {
        if (key_exists($items, $file1) && key_exists($items, $file2)) {
            if (is_array($file1[$items]) && is_array($file2[$items])) {
                $acc[] = setMessage($items, getDiff($file1[$items], $file2[$items]), 'array');
//                return [$items => ['children' => getDiff($file1[$items], $file2[$items])]];
            } elseif ($file1[$items] === $file2[$items]) {
                $acc[] = setMessage($items, $file1[$items], 'unchanged');
            } else {
                $acc[] = setMessage($items, $file1[$items], 'deleted');
                $acc[] = setMessage($items, $file2[$items], 'added');
            }
        } elseif (key_exists($items, $file1) && !key_exists($items, $file2)) {
            $acc[] = setMessage($items, $file1[$items], 'deleted');
        } else {
            $acc[] = setMessage($items, $file2[$items], 'added');
        }
        return $acc;


//        if (key_exists($items, $file1) && key_exists($items, $file2)) {
//            if ($file1[$items] === $file2[$items]) {
//                $acc[] = setMessage($items, $file1[$items], ' ');
//
//                return $acc;
//            }
//            $acc[] = setMessage($items, $file1[$items], '-');
//            $acc[] = setMessage($items, $file2[$items], '+');
//
//            return $acc;
//        }
//        key_exists($items, $file1) ?
//            $acc[] = setMessage($items, $file1[$items], '-') :
//            $acc[] = setMessage($items, $file2[$items], '+');
//
//        return $acc;
    }, []);
}
