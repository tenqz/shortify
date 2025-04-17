<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use Tenqz\Shortify\Core\Url;
use Tenqz\Shortify\Exceptions\InvalidUrlException;

class UrlTest extends TestCase
{
    /**
     * @test
     * @dataProvider validUrlProvider
     */
    public function it_should_create_url_with_valid_url_string(string $validUrl): void
    {
        $url = new Url($validUrl);
        $this->assertEquals($validUrl, $url->getOriginalUrl());
    }

    /**
     * @test
     * @dataProvider invalidUrlProvider
     */
    public function it_should_throw_exception_when_url_is_invalid(string $invalidUrl): void
    {
        $this->expectException(InvalidUrlException::class);
        new Url($invalidUrl);
    }

    /**
     * @test
     */
    public function it_should_set_and_get_short_code(): void
    {
        $url = new Url('https://example.com');
        $url->setShortCode('abc123');
        
        $this->assertEquals('abc123', $url->getShortCode());
    }

    /**
     * @test
     */
    public function it_should_be_comparable(): void
    {
        $url1 = new Url('https://example.com');
        $url2 = new Url('https://example.com');
        $url3 = new Url('https://different.com');
        
        $this->assertTrue($url1->equals($url2));
        $this->assertFalse($url1->equals($url3));
    }

    public function validUrlProvider(): array
    {
        return [
            ['https://example.com'],
            ['http://example.com'],
            ['https://www.example.com/path/to/page'],
            ['http://example.com?param=value'],
            ['https://example.com/path/page.html#section'],
        ];
    }

    public function invalidUrlProvider(): array
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