<?php
declare(strict_types=1);

namespace PhpSnippetPost\File;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class PostsTest extends TestCase
{
    public function testFromFiles()
    {
        $root = vfsStream::setup();

        $phpFile = $root->url() . '/2017-12-01.php';
        $content = implode("\n", [
            '/*',
            'title',
            '',
            'desc1',
            '*/',
            'hello',
        ]);
        file_put_contents($phpFile, $content);

        $rubyFile = $root->url() . '/2017-12-01.rb';
        $content = implode("\n", [
            '=begin',
            'title',
            '',
            'desc1',
            '=end',
            'hello',
        ]);
        file_put_contents($rubyFile, $content);

        $paths = [$phpFile, $rubyFile];
        $posts = Posts::fromFiles($paths);
        $this->assertCount(2, $posts);
        $this->assertInstanceOf(Post::class, $posts[0]);
        $this->assertInstanceOf(Post::class, $posts[1]);
    }
}
