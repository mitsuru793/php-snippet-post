<?php

namespace PhpSnippetPost\Exception;

use InvalidArgumentException;

class NotFilePathException extends InvalidArgumentException
{
    public function __construct(string $filePath)
    {
        parent::__construct("Path must be a file: $filePath");
    }
}