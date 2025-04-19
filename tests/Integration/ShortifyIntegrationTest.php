<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Tenqz\Shortify\Shortify;
use Tenqz\Shortify\Core\Url;
use Tenqz\Shortify\Exceptions\InvalidUrlException;
use Tenqz\Shortify\Exceptions\UrlNotFoundException;
use Tenqz\Shortify\Infrastructure\Repository\InMemoryRepository;

class ShortifyIntegrationTest extends TestCase
{
    private Shortify $shortify;
    private string $validUrl = 'https://example.com/long-url';

    protected function setUp(): void
    {
        $repository = new InMemoryRepository();
        $this->shortify = new Shortify($repository);
    }

    /**
     * @test
     */
    public function testShouldShortenAndExpandUrl(): void
    {
        $url = $this->shortify->shorten($this->validUrl);
        
        $this->assertInstanceOf(Url::class, $url);
        $this->assertEquals($this->validUrl, $url->getOriginalUrl());
        $this->assertNotEmpty($url->getShortCode());
        
        $expandedUrl = $this->shortify->expand($url->getShortCode());
        
        $this->assertEquals($this->validUrl, $expandedUrl);
    }

    /**
     * @test
     */
    public function testShouldThrowExceptionWhenShorteningInvalidUrl(): void
    {
        $this->expectException(InvalidUrlException::class);
        
        $this->shortify->shorten('invalid-url');
    }

    /**
     * @test
     */
    public function testShouldThrowExceptionWhenExpandingNonexistentCode(): void
    {
        $this->expectException(UrlNotFoundException::class);
        
        $this->shortify->expand('nonexistent');
    }
} 