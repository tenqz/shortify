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
    public function it_should_generate_code_with_default_length(): void
    {
        $code = $this->codeGenerator->generate();
        
        $this->assertIsString($code);
        $this->assertEquals(6, strlen($code));
    }

    /**
     * @test
     */
    public function it_should_generate_code_with_custom_length(): void
    {
        $length = 10;
        $code = $this->codeGenerator->generate($length);
        
        $this->assertIsString($code);
        $this->assertEquals($length, strlen($code));
    }

    /**
     * @test
     */
    public function it_should_generate_different_codes_each_time(): void
    {
        $codes = [];
        $iterations = 100;
        
        for ($i = 0; $i < $iterations; $i++) {
            $codes[] = $this->codeGenerator->generate();
        }
        
        $uniqueCodes = array_unique($codes);
        $this->assertCount($iterations, $uniqueCodes);
    }

    /**
     * @test
     */
    public function it_should_use_valid_characters_for_code(): void
    {
        $code = $this->codeGenerator->generate();
        
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_~]+$/', $code);
    }
} 