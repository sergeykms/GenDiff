<?php

namespace DifferTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testDiffStylish(): void
    {
        $file1 = __DIR__ . '/fixtures/file1.json';
        $file2 = __DIR__ . '/fixtures/file2.json';
        $result = genDiff($file1, $file2, 'stylish');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected.txt', $result);

        $file1 = __DIR__ . '/fixtures/file1.yaml';
        $file2 = __DIR__ . '/fixtures/file2.yaml';
        $result = genDiff($file1, $file2, 'stylish');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected.txt', $result);

        $file1 = __DIR__ . '/fixtures/file1.yml';
        $file2 = __DIR__ . '/fixtures/file2.yml';
        $result = genDiff($file1, $file2, 'stylish');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected.txt', $result);

        $recurs1 = __DIR__ . '/fixtures/recurs1.json';
        $recurs2 = __DIR__ . '/fixtures/recurs2.json';
        $result = genDiff($recurs1, $recurs2, 'stylish');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected2.txt', $result);

        $recurs1 = __DIR__ . '/fixtures/recurs1.yaml';
        $recurs2 = __DIR__ . '/fixtures/recurs2.yaml';
        $result = genDiff($recurs1, $recurs2, 'stylish');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected2.txt', $result);

        $recurs1 = __DIR__ . '/fixtures/recurs1.yml';
        $recurs2 = __DIR__ . '/fixtures/recurs2.yml';
        $result = genDiff($recurs1, $recurs2, 'stylish');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected2.txt', $result);
    }

    public function testDiffPlain(): void
    {
        $recurs1 = __DIR__ . '/fixtures/recurs1.json';
        $recurs2 = __DIR__ . '/fixtures/recurs2.json';
        $result = genDiff($recurs1, $recurs2, 'plain');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected-plain.txt', $result);

        $recurs1 = __DIR__ . '/fixtures/recurs1.yaml';
        $recurs2 = __DIR__ . '/fixtures/recurs2.yaml';
        $result = genDiff($recurs1, $recurs2, 'plain');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected-plain.txt', $result);
    }

    public function testDiffJson(): void
    {
        $recurs1 = __DIR__ . '/fixtures/recurs1.json';
        $recurs2 = __DIR__ . '/fixtures/recurs2.json';
        $result = genDiff($recurs1, $recurs2, 'json');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected-json.txt', $result);

        $recurs1 = __DIR__ . '/fixtures/recurs1.yaml';
        $recurs2 = __DIR__ . '/fixtures/recurs2.yaml';
        $result = genDiff($recurs1, $recurs2, 'json');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected-json.txt', $result);
    }
}
