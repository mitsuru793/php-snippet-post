<?php
declare(strict_types=1);

namespace PhpSnippetPost\File;

use Illuminate\Support\Collection;

final class Lines extends Collection
{
    /** @var Line */
    protected $items;

    /**
     * Lines constructor.
     * @param array <Line> $lines
     */
    public function __construct(array $lines)
    {
        parent::__construct($lines);
    }

    public function __toString()
    {
        $joined = '';
        foreach ($this->items as $item) {
            $joined .= $item . PHP_EOL;
        }
        return rtrim($joined, PHP_EOL);
    }

    /**
     * construct from file
     * @param string $filePath
     * @return Lines
     */
    public static function fromFile(string $filePath): self
    {
        // TODO collection
        $lines = explode(PHP_EOL, file_get_contents($filePath));
        $lines = array_map(function ($line) {
            return new Line($line);
        }, $lines);
        return new static($lines);
    }

    /**
     * Get comment block without start and end lines at top.
     * @param array $startPatterns of front matter
     * @param array $endPatterns of front matter
     * @return Lines
     */
    public function frontMatter(array $startPatterns, array $endPatterns): self
    {
        Line::setCommentStartPattern($startPatterns);
        Line::setCommentEndPattern($endPatterns);

        /** @var $inComment
         *  need when
         *    1. line comment start and end pattern are same.
         *    2. no comment block
         */
        $inComment = false;
        $lines = [];
        $this->each(function ($line) use (&$lines, &$inComment) {
            /** @var Line $line */
            if (!$inComment && $line->isCommentStart()) {
                $inComment = true;
                return true;
            }
            if ($line->isCommentEnd()) {
                return false;
            }

            if ($inComment) {
                $lines[] = $line;
            }
        });
        return new static($lines);
    }
}
