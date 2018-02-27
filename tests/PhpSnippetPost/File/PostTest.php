<?php
declare(strict_types=1);

namespace PhpSnippetPost\File;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class PostTest extends TestCase
{
    public function testConstruct()
    {
        $root = vfsStream::setup();
        $file = $root->url() . '/2017-12-01.php';
        $content = implode("\n", [
            '/*',
            'title',
            '',
            'desc1',
            'desc2',
            '*/',
            'hello',
            'world',
        ]);
        file_put_contents($file, $content);

        $post = new Post($file);

        $this->assertSame($file, $post->getPath());
        $this->assertSame('title', $post->getTitle());
        $this->assertSame('2017-12-01', $post->getDate());

        $body = $post->getBody();
        $this->assertInstanceOf(Lines::class, $body);
        $this->assertSame("desc1\ndesc2", (string)$body);
    }

    public function testConstructWithEmptyFrontMatter()
    {
        $root = vfsStream::setup();
        $file = $root->url() . '/2017-12-01.php';
        $content = implode("\n", [
            '/*',
            '*/',
            'hello',
        ]);
        file_put_contents($file, $content);

        $this->expectException(UnexpectedValueException::class);
        new Post($file);
    }

    public function testConstructWith1LineFrontMatter()
    {
        $root = vfsStream::setup();
        $file = $root->url() . '/2017-12-01.php';
        $content = implode("\n", [
            '/*',
            'title',
            '*/',
            'hello',
        ]);
        file_put_contents($file, $content);

        $post = new Post($file);
        $this->assertSame('title', $post->getTitle());
        $this->assertSame('', (string)$post->getBody());
    }

    public function testConstructWithNoEmptyLine()
    {
        $root = vfsStream::setup();
        $file = $root->url() . '/2017-12-01.php';
        $content = implode("\n", [
            '/*',
            'title',
            ' ',
            '*/',
            'hello',
        ]);
        file_put_contents($file, $content);

        $this->expectException(UnexpectedValueException::class);
        new Post($file);
    }
}
