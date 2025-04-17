<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Infrastructure\Repository;

use Tenqz\Shortify\Core\Url;

/**
 * Interface for URL repository implementations.
 */
interface UrlRepositoryInterface
{
    /**
     * Save URL to the repository.
     */
    public function save(Url $url): void;

    /**
     * Find URL by short code.
     *
     * @param string $code The short code to look for
     * @return Url|null The URL object if found, null otherwise
     */
    public function findByCode(string $code): ?Url;

    /**
     * Check if a short code exists in the repository.
     *
     * @param string $code The short code to check
     * @return bool True if the code exists, false otherwise
     */
    public function exists(string $code): bool;
} 