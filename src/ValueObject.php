<?php
declare(strict_types=1);

namespace PhpSnippetPost;

/**
 * Class ValueObject
 *
 * Wrapper class for scalar value.
 *
 * new instance only 'of' method, but construct from outside.
 *
 * @package PhpSnippetPost
 */
abstract class ValueObject implements \JsonSerializable
{
    protected $value;

    protected function __construct($value)
    {
        $this->value = $value;
    }

    public static function of($value)
    {
        return new static($value);
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function isNotEmpty(): bool
    {
        return !empty($this->value);
    }

    public function jsonSerialize()
    {
        return $this->value;
    }

    public function value()
    {
        return $this->value;
    }
}
