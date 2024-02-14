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

function setMessage(string $key, mixed $value, string $mark): array
{
    return [
        'key' => $key,
        'mark' => $mark,
        'value' => getValue($value),
    ];
}

function genDiff(string $pathToFile1, string $pathToFile2,): string
{
    $file1 = parse($pathToFile1);
    $file2 = parse($pathToFile2);
    $filesKeys = array_merge(array_keys($file1), array_keys($file2));
    $uniqueFilesKeys = (array_unique($filesKeys));
    sort($uniqueFilesKeys);
    $allDiffer = getDiff($uniqueFilesKeys, $file1, $file2);
//    print_r(render($allDiffer));
//    echo "\n\n";
    return render($allDiffer);

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
