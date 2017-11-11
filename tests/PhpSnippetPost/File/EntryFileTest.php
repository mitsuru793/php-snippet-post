<?php

namespace PhpSnippetPost\File;

use Helper\Fixture;
use org\bovigo\vfs\vfsStream;
use PhpSnippetPost\Exception\DirectoryHasNoEntryFileException;
use PhpSnippetPost\Exception\NotDirectoryPathException;
use PhpSnippetPost\Exception\NotFilePathException;
use PHPUnit\Framework\TestCase;

class EntryFileTest extends TestCase
{
    protected function setUp()
    {
        Fixture::$root = vfsStream::setup()->url();
    }

    public function testConstruct()
    {
        $path = Fixture::file('found.php');
        $file = new EntryFile($path);
        $this->assertSame($path, $file->path());

        $this->expectException(NotFilePathException::class);
        new EntryFile('missing file');
    }

    public function testFromDir()
    {
        $fileNames = ['first.php', 'second.php'];
        $dir = Fixture::dir('dir');

        Fixture::file("$dir/second.php");
        $file = EntryFile::fromDir($dir, $fileNames);
        $this->assertNotSame("$dir/first.php", $file->path());
        $this->assertSame("$dir/second.php", $file->path());

        $this->expectException(NotDirectoryPathException::class);
        EntryFile::fromDir("missing_dir", $fileNames);

        $this->expectException(DirectoryHasNoEntryFileException::class);
        EntryFile::fromDir("$dir/missing.php", $fileNames);
    }

    public function testFromDirFirstMatches()
    {
        $fileNames = ['first.php', 'second.php'];
        $dir = Fixture::dir('dir');

        $path = Fixture::file("$dir/first.php");
        $file = EntryFile::fromDir($dir, $fileNames);
        $this->assertSame($path, $file->path());
    }
}
