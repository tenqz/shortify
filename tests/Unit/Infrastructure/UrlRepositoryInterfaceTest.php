<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use Tenqz\Shortify\Core\Url;
use Tenqz\Shortify\Infrastructure\Repository\UrlRepositoryInterface;

abstract class UrlRepositoryInterfaceTest extends TestCase
{
    protected UrlRepositoryInterface $repository;

    /**
     * @test
     */
    public function it_should_save_and_find_url_by_code(): void
    {
        $originalUrl = 'https://example.com/long-url';
        $shortCode = 'abc123';
        
        $url = new Url($originalUrl);
        $url->setShortCode($shortCode);
        
        $this->repository->save($url);
        
        $foundUrl = $this->repository->findByCode($shortCode);
        
        $this->assertNotNull($foundUrl);
        $this->assertEquals($originalUrl, $foundUrl->getOriginalUrl());
        $this->assertEquals($shortCode, $foundUrl->getShortCode());
    }

    /**
     * @test
     */
    public function it_should_return_null_when_code_not_found(): void
    {
        $nonExistentCode = 'nonexistent';
        
        $foundUrl = $this->repository->findByCode($nonExistentCode);
        
        $this->assertNull($foundUrl);
    }

    /**
     * @test
     */
    public function it_should_check_if_code_exists(): void
    {
        $originalUrl = 'https://example.com/another-url';
        $shortCode = 'def456';
        
        $url = new Url($originalUrl);
        $url->setShortCode($shortCode);
        
        $this->repository->save($url);
        
        $this->assertTrue($this->repository->exists($shortCode));
        $this->assertFalse($this->repository->exists('nonexistent'));
    }
} 