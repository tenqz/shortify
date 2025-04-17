<?php

declare(strict_types=1);

namespace Tenqz\Shortify\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use Tenqz\Shortify\Core\CodeGenerator;

class CodeGeneratorTest extends TestCase
{
    private CodeGenerator $codeGenerator;

    protected function setUp(): void
    {
        $this->codeGenerator = new CodeGenerator();
    }

    /**
     * @test
     */
    public function itShouldGenerateCodeWithDefaultLength(): void
    {
        $url = 'https://example.com';
        $code = $this->codeGenerator->generate($url);
        
        $this->assertIsString($code);
        $this->assertEquals(6, strlen($code));
    }

    /**
     * @test
     */
    public function itShouldGenerateCodeWithCustomLength(): void
    {
        $url = 'https://example.com';
        $length = 10;
        $code = $this->codeGenerator->generate($url, $length);
        
        $this->assertIsString($code);
        $this->assertEquals($length, strlen($code));
    }

    /**
     * @test
     */
    public function itShouldGenerateDifferentCodesEachTime(): void
    {
        $codes = [];
        $iterations = 100;
        $url = 'https://example.com';
        for ($i = 0; $i < $iterations; $i++) {
            $codes[] = $this->codeGenerator->generate($url);
        }
        
        $uniqueCodes = array_unique($codes);
        $this->assertCount($iterations, $uniqueCodes);
    }

    /**
     * @test
     */
    public function itShouldUseValidCharactersForCode(): void
    {
        $url = 'https://example.com';
        $code = $this->codeGenerator->generate($url);
        
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_~]+$/', $code);
    }
} 