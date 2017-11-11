<?php

namespace PhpSnippetPost\Exception;

use InvalidArgumentException;

final class NotDirectoryPathException extends InvalidArgumentException
{
    public function __construct(string $dirPath)
    {
        parent::__construct("Path must be a directory: $dirPath");
    }
}