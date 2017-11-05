<?php

namespace PhpSnippetPost\File;

use PhpSnippetPost\ValueObject;

/**
 * Class Line
 *
 * First, call setCommentStartPattern and setCommentEndPattern.
 *
 * @package PhpSnippetPost\File
 */
class Line extends ValueObject
{
    /** @var string */
    static private $commentStartPattern;

    /** @var string */
    static private $commentEndPattern;

    /** @var string */
    protected $value;

    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function __toString()
    {
        return $this->value;
    }

    public static function setCommentStartPattern(array $patterns): void
    {
        self::$commentStartPattern = self::joinPatternsEscaping($patterns);
    }

    public static function setCommentEndPattern(array $patterns): void
    {
        self::$commentEndPattern = self::joinPatternsEscaping($patterns);
    }

    private static function joinPatternsEscaping(array $patterns): string
    {
        $joined = '';
        foreach ($patterns as $pattern) {
            $joined .= '|' . preg_quote($pattern);
        }
        return ltrim($joined, '|');
    }

    public function isCommentStart(): bool
    {
        return preg_match('~^(' . self::$commentStartPattern . ')$~', $this->value);
    }

    public function isCommentEnd(): bool
    {
        return preg_match('~^(' . self::$commentEndPattern . ')$~', $this->value);
    }
}
