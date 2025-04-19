<?php

declare(strict_types=1);

namespace Tenqz\Shortify;

use Tenqz\Shortify\Core\Url;
use Tenqz\Shortify\Core\Shortener;
use Tenqz\Shortify\Core\CodeGenerator;
use Tenqz\Shortify\Infrastructure\Repository\UrlRepositoryInterface;
use Tenqz\Shortify\Exceptions\InvalidUrlException;
use Tenqz\Shortify\Exceptions\UrlNotFoundException;

/**
 * Shortify - URL shortener facade.
 */
final class Shortify
{
    private Shortener $shortener;

    /**
     * @param UrlRepositoryInterface $repository
     */
    public function __construct(?UrlRepositoryInterface $repository)
    {
        $this->shortener = new Shortener(
            $repository,
            new CodeGenerator()
        );
    }

    /**
     * Shorten a URL.
     *
     * @param string $url URL to shorten
     * @return Url Shortened URL object
     * @throws InvalidUrlException When URL is invalid
     */
    public function shorten(string $url): Url
    {
        return $this->shortener->shorten($url);
    }

    /**
     * Expand a short code to the original URL.
     *
     * @param string $code Short code to expand
     * @return string Original URL
     * @throws UrlNotFoundException When code is not found
     */
    public function expand(string $code): string
    {
        return $this->shortener->expand($code);
    }
}
