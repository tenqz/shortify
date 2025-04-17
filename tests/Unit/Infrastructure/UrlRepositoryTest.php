<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use Tenqz\Shortify\Core\Url;
use Tenqz\Shortify\Infrastructure\Repository\UrlRepositoryInterface;
use Tenqz\Shortify\Infrastructure\Repository\InMemoryRepository;
class UrlRepositoryTest extends TestCase
{
    protected UrlRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryRepository();
    }

    public function testShouldSaveAndFindUrlByCode(): void
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

    public function testShouldReturnNullWhenCodeNotFound(): void
    {
        $nonExistentCode = 'nonexistent';
        
        $foundUrl = $this->repository->findByCode($nonExistentCode);
        
        $this->assertNull($foundUrl);
    }
    
    public function testShouldCheckIfCodeExists(): void
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
