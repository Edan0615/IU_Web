<?php

namespace Tests\Unit;

use App\Services\CognitiveAnalysisService;
use App\Services\GroqService;
use Tests\TestCase;

class CognitiveAnalysisServiceTest extends TestCase
{
    /**
     * Test normal valid JSON completion parsing.
     */
    public function test_parse_response_with_valid_json(): void
    {
        /** @var GroqService $groqMock */
        $groqMock = $this->createMock(GroqService::class);
        $service = new CognitiveAnalysisService($groqMock);

        $json = json_encode([
            'message' => 'Counseling response statement.',
            'dominant_function' => 'Fi',
            'rotation_target' => 'Te',
            'scores' => ['Ti' => 5, 'Te' => 20, 'Fi' => 28, 'Fe' => 15, 'Ni' => 10, 'Ne' => 12, 'Si' => 8, 'Se' => 6],
            'emotional_clarity_score' => 0.9,
            'cognitive_reasoning' => 'Strong internal values reflection detected.'
        ]);

        $result = $service->parseResponse($json);

        $this->assertEquals('Counseling response statement.', $result['reply_text']);
        $this->assertEquals('Fi', $result['primary_function']);
        $this->assertEquals('Te', $result['rotation_target']);
        $this->assertEquals(0.9, $result['emotional_clarity_score']);
        $this->assertEquals('Strong internal values reflection detected.', $result['cognitive_reasoning']);
    }

    /**
     * Test extreme edge case: Markdown fence wrapped JSON string parsing.
     */
    public function test_parse_response_strips_markdown_fences(): void
    {
        /** @var GroqService $groqMock */
        $groqMock = $this->createMock(GroqService::class);
        $service = new CognitiveAnalysisService($groqMock);

        $rawMarkdown = "```json\n" . json_encode([
            'message' => 'Markdown wrapped reply.',
            'dominant_function' => 'Ne',
            'scores' => ['Ne' => 25],
            'cognitive_reasoning' => 'Exploring multiple paths.'
        ]) . "\n```";

        $result = $service->parseResponse($rawMarkdown);

        $this->assertEquals('Markdown wrapped reply.', $result['reply_text']);
        $this->assertEquals('Ne', $result['primary_function']);
        $this->assertEquals('Exploring multiple paths.', $result['cognitive_reasoning']);
    }

    /**
     * Test extreme edge case: Malformed / non-JSON raw string fallback.
     */
    public function test_parse_response_handles_malformed_non_json_gracefully(): void
    {
        /** @var GroqService $groqMock */
        $groqMock = $this->createMock(GroqService::class);
        $service = new CognitiveAnalysisService($groqMock);

        $rawText = "Sorry, I cannot process this request cleanly as JSON.";

        $result = $service->parseResponse($rawText, [
            'primary_function' => 'Fi',
            'secondary_function' => 'Ne',
            'scores' => ['Fi' => 20]
        ]);

        $this->assertEquals('Sorry, I cannot process this request cleanly as JSON.', $result['reply_text']);
        $this->assertEquals('Fi', $result['primary_function']);
        $this->assertIsArray($result['scores']);
    }

    /**
     * Test analyzeUserMessage with LLM mock output and score clamping (1-30 scale).
     */
    public function test_analyze_user_message_clamps_out_of_bound_scores(): void
    {
        $groqMock = $this->mock(GroqService::class);
        $groqMock->shouldReceive('generateCompletion')
            ->once()
            ->andReturn(json_encode([
                'primary_function' => 'Ti',
                'secondary_function' => 'Ne',
                'scores' => [
                    'Ti' => 999, // Over max bound -> should clamp to 30
                    'Te' => -50, // Under min bound -> should clamp to 1
                    'Fi' => 15,
                ],
                'emotional_clarity_score' => 0.75,
                'cognitive_reasoning' => 'Analytical structure breakdown.'
            ]));

        $service = new CognitiveAnalysisService($groqMock);
        $analysis = $service->analyzeUserMessage('Looking into complex logical relationships.');

        $this->assertEquals('Ti', $analysis['primary_function']);
        $this->assertEquals(30, $analysis['scores']['Ti']);
        $this->assertEquals(1, $analysis['scores']['Te']);
        $this->assertEquals(15, $analysis['scores']['Fi']);
    }

    /**
     * Test extreme edge case: LLM failure triggers fallback spectrum generation without throwing exceptions.
     */
    public function test_analyze_user_message_fallback_spectrum_on_empty_response(): void
    {
        $groqMock = $this->mock(GroqService::class);
        $groqMock->shouldReceive('generateCompletion')
            ->once()
            ->andReturn('');

        $service = new CognitiveAnalysisService($groqMock);
        $analysis = $service->analyzeUserMessage('Testing fallback spectrum generation.');

        $this->assertNotEmpty($analysis['primary_function']);
        $this->assertNotEmpty($analysis['secondary_function']);
        $this->assertCount(8, $analysis['scores']);
        $this->assertNotEmpty($analysis['cognitive_reasoning']);
    }
}
