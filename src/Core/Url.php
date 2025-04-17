<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Core;

use Tenqz\Shortify\Exceptions\InvalidUrlException;

/**
 * URL value object.
 */
class Url
{
    private string $originalUrl;
    private ?string $shortCode;

    /**
     * @param string $url The original URL
     * @throws InvalidUrlException When the URL is invalid
     */
    public function __construct(string $url)
    {
        if (!$this->isValidUrl($url)) {
            throw new InvalidUrlException($url);
        }

        $this->originalUrl = $url;
        $this->shortCode = null;
    }

    /**
     * Set the short code for this URL.
     *
     * @param string $code The short code
     * @return void
     */
    public function setShortCode(string $code): void
    {
        $this->shortCode = $code;
    }

    /**
     * Get the original URL.
     *
     * @return string The original URL
     */
    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    /**
     * Get the short code.
     *
     * @return string|null The short code or null if not set
     */
    public function getShortCode(): ?string
    {
        return $this->shortCode;
    }

    /**
     * Compare this URL with another URL.
     *
     * @param Url $otherUrl The other URL to compare with
     * @return bool True if the URLs are equal, false otherwise
     */
    public function equals(Url $otherUrl): bool
    {
        return $this->originalUrl === $otherUrl->originalUrl;
    }

    /**
     * Check if a URL is valid.
     *
     * @param string $url The URL to validate
     * @return bool True if the URL is valid, false otherwise
     */
    private function isValidUrl(string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        // Check that URL has a valid format and contains valid scheme
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check that http or https scheme is used
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array($scheme, ['http', 'https'])) {
            return false;
        }

        // Check that host is present
        $host = parse_url($url, PHP_URL_HOST);
        if (empty($host)) {
            return false;
        }

        return true;
    }
}
