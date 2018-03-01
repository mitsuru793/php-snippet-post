<?php
declare(strict_types=1);

namespace PhpSnippetPost;

use SplFileInfo;

final class Path
{
    /** @var string */
    private $value;

    /**
     * Path constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = new SplFileInfo($value);
    }

    public function __toString()
    {
        return $this->value->getPathname();
    }

    public function renameWithDate(array $words): self
    {
        $ext = $this->value->isDir() ? '' : '.' . $this->value->getExtension();

        $renamed = sprintf('%s/%s_%s%s',
            $this->value->getPath(),
            date('Y-m-d'),
            implode('_', $words),
            $ext
        );
        return new self($renamed);
    }
}
