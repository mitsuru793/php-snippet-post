<?php
declare(strict_types=1);

namespace Helper;

final class Fixture
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

    /**
     * create directories at each path
     * @param string[] $paths
     * @return string[] created directory names which doesn't have failed one
     */
    public static function dirs(array $paths): array
    {
        $paths = array_map(function ($path) {
            return self::dir($path);
        }, $paths);
        return array_filter($paths);
    }
}