<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Infrastructure\Repository;

use Tenqz\Shortify\Core\Url;

/**
 * Simple in-memory repository implementation using arrays.
 */
class InMemoryRepository implements UrlRepositoryInterface
{
    private array $urls = [];

    public function save(Url $url): void
    {
        $code = $url->getShortCode();
        if ($code !== null) {
            $this->urls[$code] = $url;
        }
    }

    public function findByCode(string $code): ?Url
    {
        if (empty($code)) {
            return null;
        }

        return $this->urls[$code] ?? null;
    }

    public function exists(string $code): bool
    {
        return isset($this->urls[$code]);
    }
}
