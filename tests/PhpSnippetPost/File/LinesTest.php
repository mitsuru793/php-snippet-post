<?php

namespace PhpSnippetPost\File;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class LinesTest extends TestCase
{
    public function testToString()
    {
        $lines = new Lines([Line::of('v1'), Line::of('v2')]);
        $this->assertSame("v1\nv2", (string)$lines);
    }

    public function testFromFile()
    {
        $root = vfsStream::setup();
        $file = $root->url() . '/text';
        $expected = [
            'v1',
            'v2',
        ];
        $content = implode(PHP_EOL, $expected);
        file_put_contents($file, $content);

        $lines = Lines::fromFile($file);
        $this->assertInstanceOf(Lines::class, $lines);

        $actual = ($lines->map(function ($line) {
            return $line->value();
        })->all());
        $this->assertSame($expected, $actual);

        // TODO missing file
    }

    public function testFrontMatter()
    {
        $content = [
            '--',
            'val1',
            '',
            'val2',
            '--',
            'content',
        ];
        $lines = array_map(function ($line) {
            return Line::of($line);
        }, $content);
        $lines = new Lines($lines);

        $frontMatter = $lines->frontMatter(['--'], ['--']);
        $this->assertInstanceOf(Lines::class, $frontMatter);

        $expected = [
            'val1',
            '',
            'val2',
        ];
        $actual = ($frontMatter->map(function ($line) {
            return $line->value();
        })->all());
        $this->assertSame($expected, $actual);
    }

    public function testFrontMatterWithoutCommentBlock()
    {
        $content = [
            'val',
            'content',
        ];
        $lines = array_map(function ($line) {
            return Line::of($line);
        }, $content);
        $lines = new Lines($lines);

        $frontMatter = $lines->frontMatter(['--'], ['--']);
        $this->assertTrue($frontMatter->isEmpty());
    }
}
