<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Tenqz\Shortify\Core\Shortener;
use Tenqz\Shortify\Core\Url;
use Tenqz\Shortify\Core\CodeGenerator;
use Tenqz\Shortify\Infrastructure\Repository\UrlRepositoryInterface;
use Tenqz\Shortify\Exceptions\UrlNotFoundException;

class ShortenerTest extends TestCase
{
    private Shortener $shortener;
    private MockObject $urlRepository;
    private MockObject $codeGenerator;

    protected function setUp(): void
    {
        $this->urlRepository = $this->createMock(UrlRepositoryInterface::class);
        $this->codeGenerator = $this->createMock(CodeGenerator::class);
        $this->shortener = new Shortener($this->urlRepository, $this->codeGenerator);
    }

    /**
     * @test
     */
    public function itShouldShortenUrlAndSaveToRepository(): void
    {
        $originalUrl = 'https://example.com/long-url';
        $shortCode = 'abc123';

        $this->codeGenerator->expects($this->once())
            ->method('generate')
            ->willReturn($shortCode);

        $this->urlRepository->expects($this->once())
            ->method('exists')
            ->with($shortCode)
            ->willReturn(false);

        $this->urlRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Url $url) use ($originalUrl, $shortCode) {
                return $url->getOriginalUrl() === $originalUrl && $url->getShortCode() === $shortCode;
            }));

        $result = $this->shortener->shorten($originalUrl);

        $this->assertInstanceOf(Url::class, $result);
        $this->assertEquals($originalUrl, $result->getOriginalUrl());
        $this->assertEquals($shortCode, $result->getShortCode());
    }

    /**
     * @test
     */
    public function itShouldGenerateNewCodeIfCodeAlreadyExists(): void
    {
        $originalUrl = 'https://example.com/long-url';
        $existingCode = 'abc123';
        $newCode = 'def456';

        $this->codeGenerator->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls($existingCode, $newCode);

        $this->urlRepository->expects($this->exactly(2))
            ->method('exists')
            ->withConsecutive([$existingCode], [$newCode])
            ->willReturnOnConsecutiveCalls(true, false);

        $this->urlRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Url $url) use ($originalUrl, $newCode) {
                return $url->getOriginalUrl() === $originalUrl && $url->getShortCode() === $newCode;
            }));

        $result = $this->shortener->shorten($originalUrl);

        $this->assertEquals($newCode, $result->getShortCode());
    }

    /**
     * @test
     */
    public function itShouldExpandShortCodeToOriginalUrl(): void
    {
        $shortCode = 'abc123';
        $originalUrl = 'https://example.com/long-url';
        $url = new Url($originalUrl);
        $url->setShortCode($shortCode);

        $this->urlRepository->expects($this->once())
            ->method('findByCode')
            ->with($shortCode)
            ->willReturn($url);

        $result = $this->shortener->expand($shortCode);

        $this->assertEquals($originalUrl, $result);
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionWhenCodeNotFound(): void
    {
        $shortCode = 'abc123';

        $this->urlRepository->expects($this->once())
            ->method('findByCode')
            ->with($shortCode)
            ->willReturn(null);

        $this->expectException(UrlNotFoundException::class);

        $this->shortener->expand($shortCode);
    }
} 