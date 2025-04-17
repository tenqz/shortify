<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Core;

use Random\Randomizer;

/**
 * Code generator for short URLs.
 */
class CodeGenerator
{
    /**
     * The alphabet to use for the code.
     */
    private string $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    /**
     * Default code length.
     */
    public const DEFAULT_LENGTH = 6;

    /**
     * Generate a code for a given URL.
     *
     * @param string $url The URL to generate a code for
     * @param int $length The length of the code to generate
     * @return string The generated code
     */
    public function generate(string $url, int $length = self::DEFAULT_LENGTH): string
    {
        $randomizer = new Randomizer();
        $bytes = $randomizer->getBytesFromString($this->alphabet, $length);

        return substr(md5($url . microtime(true) . $bytes), 0, $length);
    }
}
