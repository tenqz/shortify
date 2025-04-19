<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tenqz\Shortify\Core\Url;
use Tenqz\Shortify\Exceptions\InvalidUrlException;

class UrlTest extends TestCase
{
    /**
     * Test creates URL with valid URL string
     */
    #[DataProvider('validUrlProvider')]
    public function testCreatesUrlWithValidUrlString(string $validUrl): void
    {
        $url = new Url($validUrl);
        $this->assertEquals($validUrl, $url->getOriginalUrl());
    }

    /**
     * Test throws exception when URL is invalid
     */
    #[DataProvider('invalidUrlProvider')]
    public function testThrowsExceptionWhenUrlIsInvalid(string $invalidUrl): void
    {
        $this->expectException(InvalidUrlException::class);
        new Url($invalidUrl);
    }

    public function testSetsAndGetsShortCode(): void
    {
        $shortCode = 'abc123';
        $url = new Url('https://example.com');
        $url->setShortCode($shortCode);
        
        $this->assertEquals($shortCode, $url->getShortCode());
    }

    public function testIsComparable(): void
    {
        $url1 = new Url('https://example.com');
        $url2 = new Url('https://example.com');
        $url3 = new Url('https://different.com');
        
        $this->assertTrue($url1->equals($url2));
        $this->assertFalse($url1->equals($url3));
    }

    public static function validUrlProvider(): array
    {
        return [
            ['https://example.com'],
            ['http://example.com'],
            ['https://www.example.com/path/to/page'],
            ['http://example.com?param=value'],
            ['https://example.com/path/page.html#section'],
        ];
    }

    public static function invalidUrlProvider(): array
    {
        return [
            ['invalid-url'],
            ['example.com'], // without scheme
            ['http://'], // only scheme
            [''], // empty string
            ['ftp://example.com'], // unsupported scheme
        ];
    }
}
