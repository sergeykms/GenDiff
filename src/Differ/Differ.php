<?php

namespace App\Differ;

//function flat_array($array) {
//    foreach ($array as $value) {
//        $resu
//        if(is_array($value)) {
//            $result=array_merge($result,flat_array($value));
//        }
//        else
//        {
//            $result[]=$value;
//        }
//    }
//    return $result;
//}

function render($diff)
{
    $format = '  %s  %s : %s';
    $result = "{\n";
    foreach ($diff as $items) {
        $result .= sprintf($format, $items["mark"], $items["key"], (string)$items["value"]) . "\n";
    }

    return $result . "{\n";
}

function getValue($value)
{
    switch (gettype($value)) {
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'NULL':
            return 'null';
        default:
            return $value;
    }
}

function parseFile(string $pathToFile): mixed
{
    $content = file_get_contents($pathToFile);

    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
}

function setMessage(string $key, mixed $value, string $mark)
{
    return [
        'key'   => $key,
        'mark'  => $mark,
        'value' => getValue($value),
    ];
}

function genDiff(string $pathToFile1, string $pathToFile2)
{
    $file1           = parseFile($pathToFile1);
    $file2           = parseFile($pathToFile2);
    $filesKeys       = array_merge(array_keys($file1), array_keys($file2));
    $uniqueFilesKeys = (array_unique($filesKeys));
    sort($uniqueFilesKeys);
    $allDiffer = getDiff($uniqueFilesKeys, $file1, $file2);
    print_r(render($allDiffer));
}

function getDiff(array $uniqueFilesKeys, array $file1, array $file2): array
{
    return array_reduce($uniqueFilesKeys, function ($acc, $items) use ($file1, $file2) {
        if (key_exists($items, $file1) && key_exists($items, $file2)) {
            if ($file1[$items] === $file2[$items]) {
                $acc[] = setMessage($items, $file1[$items], ' ');

                return $acc;
            }
            $acc[] = setMessage($items, $file1[$items], '-');
            $acc[] = setMessage($items, $file2[$items], '+');

            return $acc;
        }
        key_exists($items, $file1) ?
            $acc[] = setMessage($items, $file1[$items], '-') :
            $acc[] = setMessage($items, $file2[$items], '+');

        return $acc;
    }, []);
}

