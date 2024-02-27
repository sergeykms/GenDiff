<?php

namespace App\Stylish;

function renderDiff(array $diff): string
{
//    print_r($diff);
//            echo "\n\n ===================================================================";
    $format = '  %s %s: %s';
//    $result = sprintf($format, " ", $diff["key"], " ") . "\n";
    $result = "";
    foreach ($diff as $items) {
//        print_r($items);
//        echo "\n\n ===================================================================";
//        $result .= sprintf($format, " ", $items["key"], " ") . "\n";
        if (is_array($items['value'])) {
            $result .= renderDiff($items['value']);
        } else {
            $result .= sprintf($format, " ", $items["key"], " ") . "\n";
        }
//        switch ($items["type"]) {
////            case 'node':
//////                print_r(sprintf($format, " ", $items["key"], " ") . "\n");
////                if (is_array($items["value"])) {
////                    print_r(sprintf($format, " ", $items["key"], renderDiff($items["value"])) . "\n");
//////                    renderDiff($items["value"]);
////                } else {
//////                    print_r(sprintf($format, " ", $items["key"], " ") . "\n");
////                }
////                break;
//            case 'unchanged':
//                if (is_array($items["value"])) {
//                    print_r(sprintf($format, " ", $items["key"], " ") . "\n");
//                    print_r(sprintf($format, " ", $items["key"], renderDiff($items["value"])) . "\n");
//                    break;
//                } else {
//                    print_r(sprintf($format, " ", $items["key"], (string)$items["value"]) . "\n");
//                }
//                break;
//            case 'deleted':
//                if (is_array($items["value"])) {
//                    print_r(sprintf($format, "-", $items["key"], " ") . "\n");
//                    print_r(sprintf($format, "-", $items["key"], renderDiff($items["value"])) . "\n");
//                    break;
//                } else {
//                    print_r(sprintf($format, "-", $items["key"], (string)$items["value"]) . "\n");
//                }
//                break;
//            case 'added':
//                if (is_array($items["value"])) {
//                    print_r(sprintf($format, "+", $items["key"], renderDiff($items["value"])) . "\n");
//                    break;
//                } else {
//                    print_r(sprintf($format, "+", $items["key"], (string)$items["value"]) . "\n");
//                }
//                break;
//        }
    }
//    echo "\n\n ===================================================================";
//    print_r($result);
    return $result;
}

function stylish(array $allDiffer): string
{
    return renderDiff($allDiffer);

//    array_map(function ($items) {
//        if (is_array($items) && key_exists('mark', $items) ) {
//            renderDiff($items);
//        }
//    }, $allDiffer);
//    return $result;
}

