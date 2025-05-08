<p align="center">
<a href="https://github.com/tenqz/shortify/actions"><img src="https://github.com/tenqz/shortify/workflows/Tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/tenqz/shortify"><img src="https://img.shields.io/packagist/dt/tenqz/shortify" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/tenqz/shortify"><img src="https://img.shields.io/packagist/v/tenqz/shortify" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/tenqz/shortify"><img src="https://img.shields.io/packagist/l/tenqz/shortify" alt="License"></a>
</p>

# Shortify

🚀 Lightweight PHP 8.3+ library for URL shortening that follows SOLID and DDD principles.

## Requirements

- PHP 8.3 or higher
- Composer

## Installation

```bash
composer require tenqz/shortify
```

## Features

- PHP 8.3+ 
- PSR-12 compliant
- SOLID/DDD: clear layer separation, ValueObject, Dependency Injection
- TDD: code covered with tests
- Minimalistic and clean code (KISS/DRY)

## Usage

### Basic Usage

```php
<?php

use Tenqz\Shortify\Shortify;
use Tenqz\Shortify\Infrastructure\Repository\UrlRepositoryInterface;

// 1. Create your implementation of UrlRepositoryInterface
class YourRepository implements UrlRepositoryInterface
{
    // Implement save, findByCode, exists methods
}

// 2. Create a Shortify instance with your repository
$repository = new YourRepository();
$shortify = new Shortify($repository);

// 3. Shorten a URL
try {
    $url = $shortify->shorten('https://example.com/very-long-url');
    echo "Short code: " . $url->getShortCode(); // For example "xB4p2q"
} catch (\Tenqz\Shortify\Exceptions\InvalidUrlException $e) {
    echo "Error: " . $e->getMessage();
}

// 4. Get the original URL from the code
try {
    $originalUrl = $shortify->expand('xB4p2q');
    echo "Original URL: " . $originalUrl;
} catch (\Tenqz\Shortify\Exceptions\UrlNotFoundException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Creating Your Own Repository

The library doesn't provide specific repository implementations, which allows you to create your own implementations for various data stores. Here's an example of a simple in-memory implementation:

```php
<?php

use Tenqz\Shortify\Infrastructure\Repository\UrlRepositoryInterface;
use Tenqz\Shortify\Core\Url;

class InMemoryUrlRepository implements UrlRepositoryInterface
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
        return $this->urls[$code] ?? null;
    }

    public function exists(string $code): bool
    {
        return isset($this->urls[$code]);
    }
}
```

## Architecture

The library is built according to Domain-Driven Design (DDD) principles and is divided into the following layers:

### Core (Domain)

- `Url.php` - URL ValueObject with validation
- `Shortener.php` - Main URL shortening logic
- `CodeGenerator.php` - Unique code generator

### Infrastructure

- `UrlRepositoryInterface.php` - Repository interface for URL storage

### Exceptions

- `InvalidUrlException.php` - Exception for invalid URLs
- `UrlNotFoundException.php` - Exception when a URL with the given code is not found

### Shortify (Facade)

- `Shortify.php` - Facade for convenient library usage

## Testing

```bash
composer test
```

## License

MIT