<?php

namespace DifferTest;
use PHPUnit\Framework\TestCase;

use function App\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testDiff(): void
    {
        $file1 = __DIR__ . "/fixtures/file1.json";
        $file2 = __DIR__ . "/fixtures/file2.json";
        $result = genDiff($file1, $file2, "json");
        $expected = file_get_contents(__DIR__ . "/fixtures/expected.txt");
        $this->assertEquals($expected, $result);
    }
}