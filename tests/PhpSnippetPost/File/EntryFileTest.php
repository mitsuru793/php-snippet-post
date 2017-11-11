<?php

namespace PhpSnippetPost\File;

use org\bovigo\vfs\vfsStream;
use PhpSnippetPost\Exception\DirectoryHasNoEntryFileException;
use PhpSnippetPost\Exception\NotDirectoryPathException;
use PhpSnippetPost\Exception\NotFilePathException;
use PHPUnit\Framework\TestCase;

class EntryFileTest extends TestCase
{
    /** @var string */
    private $root;

    public function testConstruct()
    {
        $path = $this->makeFile('found.php');
        $file = new EntryFile($path);
        $this->assertSame($path, $file->path());

        $this->expectException(NotFilePathException::class);
        new EntryFile('missing file');
    }

    public function testFromDir()
    {
        $fileNames = ['first.php', 'second.php'];
        $dir = $this->makeDir('dir');

        $this->makeFile("$dir/second.php");
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
        $dir = $this->makeDir('dir');

        $path = $this->makeFile("$dir/first.php");
        $file = EntryFile::fromDir($dir, $fileNames);
        $this->assertSame($path, $file->path());
    }

    protected function setUp()
    {
        parent::setUp();
        $this->root = vfsStream::setup()->url();
    }

    private function makeFile($path): string
    {
        if (!preg_match("~^{$this->root}~", $path)) {
            $path = "$this->root/$path";
        }
        return touch($path) ? $path : '';
    }

    private function makeDir($dir): string
    {
        $path = $this->root . "/$dir";
        return mkdir($path) ? $path : '';
    }
}
