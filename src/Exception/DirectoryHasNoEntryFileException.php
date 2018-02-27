<?php
declare(strict_types=1);

namespace PhpSnippetPost\Exception;

use InvalidArgumentException;

final class DirectoryHasNoEntryFileException extends InvalidArgumentException
{
    public function __construct(string $dirPath)
    {
        parent::__construct("Directory must has entry file: $dirPath");
    }
}