<?php
declare(strict_types=1);

namespace PhpSnippetPost\File;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class LinesTest extends TestCase
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
        $content = implode(PHP_EOL, ['v1', 'v2']);
        file_put_contents($file, $content);

        $lines = Lines::fromFile($file);
        $this->assertInstanceOf(Lines::class, $lines);
        $this->assertSame($content, (string)$lines);

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

        $expected = implode(PHP_EOL, ['val1', '', 'val2']);
        $this->assertSame($expected, (string)$frontMatter);
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
