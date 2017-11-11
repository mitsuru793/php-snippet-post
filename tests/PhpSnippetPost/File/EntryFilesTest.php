<?php

namespace PhpSnippetPost\File;

use Helper\Fixture;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class EntryFilesTest extends TestCase
{
    protected function setUp()
    {
        Fixture::$root = vfsStream::setup()->url();
    }

    public function testFromPaths()
    {
        $file = Fixture::file('/main.php');
        [$dir1, $dir2] = Fixture::dirs(['dir1', 'dir2']);
        $dirFile = Fixture::file("dir1/entry.php");
        Fixture::file("dir2/not-entry.php");
        $paths = [
            $file,
            $dir1,
            // not be contained by collection
            $dir2, // because doesn't has a entry file
            'missing.php', // because doesn't exist
        ];

        $files = EntryFiles::fromPaths($paths, ['entry.php']);
        $this->assertInstanceOf(EntryFiles::class, $files);
        $this->assertCount(2, $files);
        $this->assertSame($file, $files[0]->path());
        $this->assertSame($dirFile, $files[1]->path());
    }
}
