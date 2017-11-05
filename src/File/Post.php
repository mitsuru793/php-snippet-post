<?php

namespace PhpSnippetPost\File;

use UnexpectedValueException;

/**
 * Class Post
 *
 * file name:
 *      contains date
 *      2017-08-23_hoge_bar
 *
 * format:
 *      ---
 *      title
 *
 *      desc...
 *      ---
 *      content...
 *
 * @package PhpSnippetPost\File
 */
class Post
{
    // TODO other file type
    const COMMENT_BLOCK = [
        'php' => [['/*', '<!--'], ['*/', '-->']],
        'rb' => [['=begin'], ['=end']],
    ];

    private const  DATE_PATTERN = '/\d{4}-\d{2}-\d{2}/';

    /** @var string */
    private $path;

    /** @var string */
    private $title;

    /** @var string */
    private $date;

    /** @var Lines */
    private $body;

    public function __construct(string $filePath)
    {
        $this->path = $filePath;

        // parse file
        $ext = pathinfo($this->path, PATHINFO_EXTENSION);
        $commentPatterns = self::COMMENT_BLOCK[$ext];
        $lines = Lines::fromFile($filePath)->frontMatter($commentPatterns[0], $commentPatterns[1]);
        if ($lines->count() < 3) {
            throw new UnexpectedValueException('Fromt matter must consists of more than 3 lines: $filePath');
        }

        preg_match(self::DATE_PATTERN, $filePath, $matches);
        $this->title = $lines->get(0)->value();
        $this->date = $matches[0];
        $this->body = $lines->slice(2);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getBody(): Lines
    {
        return $this->body;
    }
}
