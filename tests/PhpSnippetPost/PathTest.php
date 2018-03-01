<?php
declare(strict_types=1);

namespace PhpSnippetPost;

use Helper\Fixture;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class PathTest extends TestCase
{
    protected function setUp()
    {
        Fixture::$root = vfsStream::setup()->url();
    }

    public function testRenameWithDate()
    {
        $today = date('Y-m-d');

        // File
        $target = new Path(Fixture::file("hoge.php"));

        $renamed = (string)$target->renameWithDate(['hello', 'world']);
        $this->assertSame(Fixture::$root . "/{$today}_hello_world.php", $renamed);

        // Dir
        $target = new Path(Fixture::dir("hoge/"));

        $renamed = (string)$target->renameWithDate(['hello', 'world']);
        $this->assertSame(Fixture::$root . "/{$today}_hello_world", $renamed);
    }
}
