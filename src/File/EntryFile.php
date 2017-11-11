<?php

namespace PhpSnippetPost\File;

use PhpSnippetPost\Exception\DirectoryHasNoEntryFileException;
use PhpSnippetPost\Exception\NotDirectoryPathException;
use PhpSnippetPost\Exception\NotFilePathException;
use Prophecy\Exception\InvalidArgumentException;

/**
 * Class EntryFile
 *
 * Generate a post using a entry file
 *
 * @package PhpSnippetPost\File
 */
class EntryFile
{
    /** @var string */
    protected $path;

    /**
     * EntryFile constructor.
     * @param string $filePath
     * @throws InvalidArgumentException
     */
    public function __construct(string $filePath)
    {
        if (!is_file($filePath)) {
            throw new NotFilePathException($filePath);
        }
        $this->path = $filePath;
    }

    /**
     * Find and build a entry file from a directory path
     * @param string $dirPath
     * @param array $entryFileNames first matched will be used.
     * @return EntryFile
     * @throws DirectoryHasNoEntryFileException
     * @throws NotDirectoryPathException
     */
    public static function fromDir(string $dirPath, array $entryFileNames): self
    {
        if (!is_dir($dirPath)) {
            throw new NotDirectoryPathException($dirPath);
        }

        foreach ($entryFileNames as $name) {
            $file = "$dirPath/$name";
            if (file_exists($file)) {
                return new self($file);
            }
        }
        throw new DirectoryHasNoEntryFileException($dirPath);
    }

    /**
     * Get a path of the entry file.
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    public function __toString(): string
    {
        return $this->path;
    }
}
