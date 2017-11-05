<?php

namespace PhpSnippetPost\File;

use Illuminate\Support\Collection;

class Posts extends Collection
{
    public function __construct($posts)
    {
        parent::__construct($posts);
    }

    public static function fromFiles(array $paths): self
    {
        $posts = array_map(function ($path) {
            return new Post($path);
        }, $paths);
        return new static($posts);
    }
}

