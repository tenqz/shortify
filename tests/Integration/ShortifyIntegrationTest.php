<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Tenqz\Shortify\Shortify;
use Tenqz\Shortify\Core\Url;
use Tenqz\Shortify\Exceptions\InvalidUrlException;
use Tenqz\Shortify\Exceptions\UrlNotFoundException;

class ShortifyIntegrationTest extends TestCase
{
    private Shortify $shortify;
    private string $validUrl = 'https://example.com/long-url';

    protected function setUp(): void
    {
        $this->shortify = new Shortify();
    }

    /**
     * @test
     */
    public function it_should_shorten_and_expand_url(): void
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
    public function it_should_throw_exception_when_shortening_invalid_url(): void
    {
        $this->expectException(InvalidUrlException::class);
        
        $this->shortify->shorten('invalid-url');
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_expanding_nonexistent_code(): void
    {
        $this->expectException(UrlNotFoundException::class);
        
        $this->shortify->expand('nonexistent');
    }
} 