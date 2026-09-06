<?php

namespace Tests\Unit;

use App\Services\CognitiveRotationService;
use Tests\TestCase;

class CognitiveRotationServiceTest extends TestCase
{
    protected CognitiveRotationService $rotationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rotationService = new CognitiveRotationService();
    }

    /**
     * Test all 8 Jungian cognitive function rotation mappings.
     */
    public function test_all_eight_cognitive_function_rotations(): void
    {
        $expected = [
            'Fi' => ['bridge' => 'Fe', 'target' => 'Te'],
            'Ti' => ['bridge' => 'Ne', 'target' => 'Se'],
            'Ni' => ['bridge' => 'Fi', 'target' => 'Se'],
            'Si' => ['bridge' => 'Fe', 'target' => 'Ne'],
            'Fe' => ['bridge' => 'Ni', 'target' => 'Fi'],
            'Te' => ['bridge' => 'Se', 'target' => 'Ti'],
            'Ne' => ['bridge' => 'Te', 'target' => 'Ni'],
            'Se' => ['bridge' => 'Si', 'target' => 'Ti'],
        ];

        foreach ($expected as $primary => $info) {
            $details = $this->rotationService->getRotationDetails($primary);
            $this->assertEquals($primary, $details['primary']);
            $this->assertEquals($info['bridge'], $details['bridge']);
            $this->assertEquals($info['target'], $details['target']);
            $this->assertStringContainsString("{$primary} → {$info['bridge']} (Bridge) → {$info['target']} (Target)", $details['vector']);
            $this->assertNotEmpty($details['strategy']);
        }
    }

    /**
     * Test extreme case: Lowercase and mixed whitespace input normalization.
     */
    public function test_rotation_details_handles_lowercase_and_whitespace(): void
    {
        $details = $this->rotationService->getRotationDetails('   ti  ');
        $this->assertEquals('Ti', $details['primary']);
        $this->assertEquals('Ne', $details['bridge']);
        $this->assertEquals('Se', $details['target']);
    }

    /**
     * Test extreme case: Unknown or invalid function input fallback.
     */
    public function test_rotation_details_fallback_for_invalid_input(): void
    {
        $invalidInputs = ['UNKNOWN', '123', '!!!', ''];

        foreach ($invalidInputs as $input) {
            $details = $this->rotationService->getRotationDetails($input);
            $this->assertNotEmpty($details['primary']);
            $this->assertNotEmpty($details['bridge']);
            $this->assertNotEmpty($details['target']);
            $this->assertNotEmpty($details['vector']);
            $this->assertNotEmpty($details['strategy']);
        }
    }

    /**
     * Test system prompt formatting contains rotation vector and JSON requirement mandate.
     */
    public function test_system_prompt_generation_includes_rotation_mandate(): void
    {
        $analysis = [
            'primary_function' => 'Ne',
            'cognitive_reasoning' => 'Scattered options detected',
        ];

        $prompt = $this->rotationService->getSystemPrompt($analysis);

        $this->assertStringContainsString('TWO-STAGE COGNITIVE ROTATION VECTOR:', $prompt);
        $this->assertStringContainsString('Ne → Te (Bridge) → Ni (Target)', $prompt);
        $this->assertStringContainsString('CRITICAL OUTPUT FORMAT REQUIREMENT:', $prompt);
    }
}
