<?php
declare(strict_types=1);

namespace PhpSnippetPost\File;

use Illuminate\Support\Collection;
use PhpSnippetPost\Exception\DirectoryHasNoEntryFileException;

final class EntryFiles extends Collection
{
    public function __construct(array $entryPointPaths)
    {
        parent::__construct($entryPointPaths);
    }

    /**
     * Find and build entry files from directory paths
     * @param string[] $paths
     * @param string[] $entryFileNames first matched will be used.
     * @return EntryFiles
     */
    public static function fromPaths(array $paths, array $entryFileNames): self
    {
        $entryFiles = [];
        foreach ($paths as $path) {
            if (is_file($path)) {
                $entryFiles[] = new EntryFile($path);
            }
            if (is_dir($path)) {
                try {
                    $entryFiles[] = EntryFile::fromDir($path, $entryFileNames);
                } catch (DirectoryHasNoEntryFileException $e) {
                    continue;
                }
            }
        }
        return new static($entryFiles);
    }
}

