<?php

namespace PhpSnippetPost\File;

use PHPUnit\Framework\TestCase;

class LineTest extends TestCase
{
    public function testConstruct()
    {
        $line = Line::of('val');
        $this->assertSame('val', $line->value());
    }

    public function testToString()
    {
        $line = Line::of('val');
        $this->assertSame('val', (string)$line);
    }

    /**
     * @dataProvider additionIsCommentStart
     */
    public function testIsCommentStart(string $value, bool $expected)
    {
        Line::setCommentStartPattern(['/**', '<!--']);
        $line = Line::of($value);
        $this->assertSame($expected, $line->isCommentStart());
    }

    public function additionIsCommentStart()
    {
        return [
            ['/**', true],
            ['<!--', true],
            ['/** ', false],
            [' /**', false],
            ['//', false],
        ];
    }

    /**
     * @dataProvider additionIsCommentEnd
     */
    public function testIsCommentEnd(string $value, bool $expected)
    {
        Line::setCommentEndPattern(['**/', '-->']);
        $line = Line::of($value);
        $this->assertSame($expected, $line->isCommentEnd());
    }

    public function additionIsCommentEnd()
    {
        return [
            ['**/', true],
            ['-->', true],
            ['**/ ', false],
            [' **/', false],
            ['//', false],
        ];
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testIsEmpty()
    {
        $this->assertTrue((new Line(''))->isEmpty());
        $this->assertFalse((new Line(' '))->isEmpty());
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testIsNotEmpty()
    {
        $this->assertFalse((new Line(''))->isNotEmpty());
        $this->assertTrue((new Line(' '))->isNotEmpty());
    }
}
