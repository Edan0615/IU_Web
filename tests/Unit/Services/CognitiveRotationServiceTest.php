<?php

namespace Tests\Unit\Services;

use App\Services\CognitiveRotationService;
use PHPUnit\Framework\TestCase;

class CognitiveRotationServiceTest extends TestCase
{
    private CognitiveRotationService $rotationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rotationService = new CognitiveRotationService();
    }

    public function test_get_system_prompt_generates_correct_rotation_vector_for_fi(): void
    {
        $analysis = ['primary_function' => 'Fi'];
        $prompt = $this->rotationService->getSystemPrompt($analysis);

        $this->assertStringContainsString('Fi -> Fe (Bridge) -> Te (Grounded Goal)', $prompt);
        $this->assertStringContainsString('Bridge Function (Soothe/Support): [Fe]', $prompt);
        $this->assertStringContainsString('Target Function (Grounded Goal): [Te]', $prompt);
    }

    public function test_get_system_prompt_generates_correct_rotation_vector_for_ti(): void
    {
        $analysis = ['primary_function' => 'Ti'];
        $prompt = $this->rotationService->getSystemPrompt($analysis);

        $this->assertStringContainsString('Ti -> Ne (Bridge) -> Se (Grounded Goal)', $prompt);
        $this->assertStringContainsString('Bridge Function (Soothe/Support): [Ne]', $prompt);
        $this->assertStringContainsString('Target Function (Grounded Goal): [Se]', $prompt);
    }

    public function test_get_system_prompt_defaults_to_fi_when_primary_function_is_missing(): void
    {
        $analysis = [];
        $prompt = $this->rotationService->getSystemPrompt($analysis);

        $this->assertStringContainsString('Fi -> Fe (Bridge) -> Te (Grounded Goal)', $prompt);
    }
}
