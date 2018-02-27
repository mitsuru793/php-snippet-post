<?php
declare(strict_types=1);

namespace PhpSnippetPost\Exception;

use InvalidArgumentException;

final class NotFilePathException extends InvalidArgumentException
{
    public function __construct(string $filePath)
    {
        parent::__construct("Path must be a file: $filePath");
    }
}