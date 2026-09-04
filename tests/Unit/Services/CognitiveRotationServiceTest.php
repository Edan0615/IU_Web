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
        $analysis = [
            'primary_function' => 'Fi',
            'loop_detected' => 'Fi-Si Isolation Loop',
            'in_loop' => true,
        ];

        $prompt = $this->rotationService->getSystemPrompt($analysis);

        $this->assertStringContainsString('Bridge Function (Soothe/Support): [Fe]', $prompt);
        $this->assertStringContainsString('Target Function (Grounded Goal): [Te]', $prompt);
        $this->assertStringContainsString('URGENT LOOP INTERVENTION', $prompt);
        $this->assertStringContainsString('First express Fe active empathy', $prompt);
    }

    public function test_get_system_prompt_generates_correct_rotation_vector_for_ti(): void
    {
        $analysis = [
            'primary_function' => 'Ti',
            'loop_detected' => 'Ti-Ni Loop',
            'in_loop' => true,
        ];

        $prompt = $this->rotationService->getSystemPrompt($analysis);

        $this->assertStringContainsString('Bridge Function (Soothe/Support): [Ne]', $prompt);
        $this->assertStringContainsString('Target Function (Grounded Goal): [Se]', $prompt);
        $this->assertStringContainsString('First use Ne to open up fresh possibilities', $prompt);
    }

    public function test_get_system_prompt_defaults_to_fi_when_primary_function_is_missing(): void
    {
        $analysis = [
            'loop_detected' => null,
            'in_loop' => false,
        ];

        $prompt = $this->rotationService->getSystemPrompt($analysis);

        $this->assertStringContainsString('Student Detected State: [Fi]', $prompt);
        $this->assertStringContainsString('Bridge Function (Soothe/Support): [Fe]', $prompt);
        $this->assertStringContainsString('Target Function (Grounded Goal): [Te]', $prompt);
    }

}
