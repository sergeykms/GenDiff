<?php

namespace Formatters\Stylish;

function renderArray(array $array, int $level): string
{
    $keys = array_keys($array);
//    echo "================\n";
//    print_r($keys);
//    echo "================\n";
    $viewArray = array_map(function ($key) use ($array, $level,) {
        $format = "\n %s %s %s: %s \n %s}";
//        $temp = "------ level {$level}\n";
//        $indent2 = str_repeat(" ", $level * 4 - 2);
        $level++;
        $indent = str_repeat(" ", $level * 4 - 2);
        $indent2 = str_repeat(" ", $level * 4 - 4);
//        $prefix = getIndent($depth) . UNMODIFIED;
        $value = sprintf($format, $indent, "", $key, getValue($array[$key], $level), $indent2);
//        $value = getValue($array[$key]);
//        return "{$indent} {$value}";
        return "{$value}";

    }, $keys);
//    $initialString = "{\n";
//    $endString = "\n"  . "}";
    return implode("", $viewArray);
//    return $body;
}


function getValue(mixed $value, int $level = 0): mixed
{
    return match (gettype($value)) {
        'boolean' => $value ? 'true' : 'false',
        'NULL' => 'null',
        'int' => $value,
        'array' => renderArray($value, $level),
        default => $value,
    };
}


//function createMessage(int $level, string $key, mixed $value, string $mark): string
//{
//    $format = '%s %s: %s';
//    $indent = $level * 4 - 2;
//    if (is_array($value)) {
//        return str_repeat(" ", $indent) . sprintf($format, $mark, $key, "")
//            . " {\n" . stylish($value, $level)
//            . str_repeat(" ", $indent) . "  }\n";
//    } else {
//        return str_repeat(" ", $indent)
////            . rtrim(sprintf($format, $mark, $key, $value))
//            . sprintf($format, $mark, $key, getValue($value))
//            . "\n";
//    }
//}


function createMessage(int $level, string $key, mixed $value, string $mark): string
{
    $format = '%s %s: %s';
//    $indent = str_repeat(" ", $level * 4 - 2);
//    $message = sprintf($format, $mark, $key, getValue($value));
    $indent = str_repeat(" ", $level * 4 - 3);
    if (is_array($value)) {
        $message = sprintf($format, $mark, $key, "");
        $message2 = renderArray($value, $level);
        return "\n{$indent}{$message}{{$message2}";
    } else {
        $message = sprintf($format, $mark, $key, getValue($value));
        return "\n{$indent}{$message}";
    }
}

function renderNode(int $level, string $key, mixed $value, string $mark): string
{
    $format = "%s %s: { %s ";
    $indent = str_repeat(" ", $level * 4 - 4);
    if (is_array($value)) {
        $message = sprintf($format, "", $key, "");
        $message2 = renderArray($value, $level);
        return "\n{$indent}{$message}{$message2}";
    } else {
        $message = sprintf($format, "", $key, getValue($value));
        return "\n{$indent}{$message}{$indent}";
    }
}

function stylish(array $diff, int $level = 1): string
{
    $message = array_reduce($diff, function ($acc, $items) use ($level) {
        $level++;
        switch ($items["type"]) {
            case 'node':
                $acc[] = renderNode($level, $items["key"], stylish($items['children'], $level), " ");
                break;
            case 'unchanged':
                $acc[] = createMessage($level, $items["key"], $items['before'], " ");
                break;
            case 'deleted':
                $acc[] = createMessage($level, $items["key"], $items['before'], "-");
                break;
            case 'added':
                $acc[] = createMessage($level, $items["key"], $items['after'], "+");
                break;
//            case 'changed':
//                $acc[] = createMessage($level, $items["key"], $items['before'], "-");
//                $acc[] = createMessage($level, $items["key"], $items['after'], "+");
//                break;
        }
        return $acc;
    }, []);
    return implode("", $message);
//    return "\n{$temp}\n}";
}

//function stylish(array $diff, int $level = 0): string
////function stylish(array $diff, int $level = 0): array
//{
//    $diffMessage =  array_map(function ($items) use ($level) {
//        $level++;
//        switch ($items["type"]) {
//            case 'node':
//                return createMessage($level, $items["key"], $items['children'], " ");
//            case 'deleted':
//                return createMessage($level, $items["key"], $items['before'], "-");
//            case 'added':
//                return createMessage($level, $items["key"], $items['after'], "-");
//            case 'added':
//                return createMessage($level, $items["key"], $items['after'], "-");
////            case 'added':
////                $acc .= createMessage($level, $items["key"], $items['value'], "+");
////                break;
////            case 'changed':
////                $deletedItems = $items["before"];
////                $addedItems = $items["after"];
////                $acc .= createMessage($level, $deletedItems["key"], $deletedItems["value"], "-");
////                $acc .= createMessage($level, $addedItems["key"], $addedItems["value"], "+");
////                break;
//        }
////        return $acc;
//    }, $diff);
//    return implode("\n", $diffMessage);
//}
