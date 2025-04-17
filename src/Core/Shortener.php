<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Core;

use Tenqz\Shortify\Infrastructure\Repository\UrlRepositoryInterface;
use Tenqz\Shortify\Exceptions\UrlNotFoundException;

/**
 * URL shortener service.
 */
class Shortener
{
    /**
     * @param UrlRepositoryInterface $repository URL repository
     * @param CodeGenerator $codeGenerator Code generator
     */
    public function __construct(
        private readonly UrlRepositoryInterface $repository,
        private readonly CodeGenerator $codeGenerator
    ) {
    }

    /**
     * Shorten a URL.
     *
     * @param string $url The URL to shorten
     * @return Url The shortened URL object
     */
    public function shorten(string $url, int $length = CodeGenerator::DEFAULT_LENGTH): Url
    {
        $url = new Url($url);
        $code = $this->codeGenerator->generate($url->getOriginalUrl(), $length);
        $url->setShortCode($code);

        $this->repository->save($url);

        return $url;
    }

    /**
     * Expand a shortened URL code to the original URL.
     *
     * @param string $code The short code to expand
     * @return string The original URL
     * @throws UrlNotFoundException When the code is not found
     */
    public function expand(string $code): string
    {
        $url = $this->repository->findByCode($code);

        if ($url === null) {
            throw new UrlNotFoundException($code);
        }

        return $url->getOriginalUrl();
    }
}
