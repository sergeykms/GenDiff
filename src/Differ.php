<?php

namespace App\Differ;

function parseFile(string $pathToFile): mixed
{
    $content = file_get_contents($pathToFile);
    $decodecContent = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    return $decodecContent;
}

function genDiff(string $pathToFile1, string $pathToFile2): array
{
    $file1 = parseFile($pathToFile1);
    $file2 = parseFile($pathToFile2);

    $filesKeys = array_merge(array_keys($file1), array_keys($file2));
    $uniqeFilesKeys = (array_unique($filesKeys));
    sort($uniqeFilesKeys);

    // $new = array_map(function($items) {
    //     return $items;
    // }, $uniqeFilesKeys);

    echo "\n";
    print_r($uniqeFilesKeys);
    echo "\n";

    return $uniqeFilesKeys;
}
