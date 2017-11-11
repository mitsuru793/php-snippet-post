<?php

namespace Helper;

class Fixture
{
    /** @var string */
    public static $root;

    /**
     * create a file at path
     * @param string $path as file
     * @return string created file name or empty as failed
     */
    public static function file(string $path): string
    {
        if (!preg_match('~^' . self::$root . '~', $path)) {
            $path = self::$root . "/$path";
        }
        return touch($path) ? $path : '';
    }

    /**
     * create a directory at path
     * @param string $path as directory
     * @return string created directory name or empty as failed
     */
    public static function dir(string $path): string
    {
        $path = self::$root . "/$path";
        return mkdir($path) ? $path : '';
    }
}