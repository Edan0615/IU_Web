<?php

namespace Tests\Unit\Services;

use App\Models\Chat;
use App\Services\CognitiveRotationService;
use App\Services\CounselingService;
use App\Services\GroqService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CounselingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_counseling_service_processes_user_message_and_persists_ai_data(): void
    {
        // 1. Create a Chat session
        $chat = Chat::create([
            'session_token' => 'test-session-123',
            'title' => 'Counseling Session',
            'dominant_function' => 'Fi',
            'in_loop' => false,
        ]);

        // 2. Mock GroqService to return AI response in JSON format
        $groqMock = Mockery::mock(GroqService::class);
        $aiJsonResponse = json_encode([
            'message' => 'I understand you are feeling anxious about exams. Take a quiet breath.',
            'dominant_function' => 'Fi',
            'rotation_target' => 'Te',
            'rotation_vector' => 'Fi -> Te',
            'in_loop' => false,
            'emotional_clarity_score' => 0.88,
            'scores' => [
                'Ti' => 5,
                'Te' => 12,
                'Fi' => 28,
                'Fe' => 10,
                'Ni' => 18,
                'Ne' => 14,
                'Si' => 8,
                'Se' => 6,
            ],
        ]);

        $groqMock->shouldReceive('generateCompletion')
            ->once()
            ->withArgs(function ($messages, $systemPrompt) {
                return is_array($messages) &&
                       count($messages) === 1 &&
                       $messages[0]['content'] === 'I feel overwhelmed by upcoming exams' &&
                       is_string($systemPrompt);
            })
            ->andReturn($aiJsonResponse);

        $rotationService = new CognitiveRotationService();
        $counselingService = new CounselingService($groqMock, $rotationService);

        // 3. Process user message
        $result = $counselingService->processUserMessage($chat, 'I feel overwhelmed by upcoming exams');

        // 4. Assert Database Persistence
        $this->assertDatabaseHas('chat_messages', [
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => 'I feel overwhelmed by upcoming exams',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'content' => 'I understand you are feeling anxious about exams. Take a quiet breath.',
        ]);

        $this->assertDatabaseHas('cognitive_states', [
            'chat_id' => $chat->id,
            'primary_function' => 'Fi',
            'emotional_clarity_score' => 0.88,
        ]);
    }

    public function test_counseling_service_handles_general_emotional_phrases_with_non_zero_scores(): void
    {
        $chat = Chat::create([
            'session_token' => 'general-phrase-session',
            'title' => 'Counseling Session',
            'dominant_function' => 'Fi',
            'in_loop' => false,
        ]);

        $groqMock = Mockery::mock(GroqService::class);
        $groqMock->shouldReceive('generateCompletion')
            ->once()
            ->andReturn("I am listening closely to what you are sharing. Take a deep breath.");

        $rotationService = new CognitiveRotationService();
        $counselingService = new CounselingService($groqMock, $rotationService);

        $result = $counselingService->processUserMessage($chat, "I don't feel quite good today!");

        $scores = $result['latest_analysis']['scores'];
        foreach (['Ti', 'Te', 'Fi', 'Fe', 'Ni', 'Ne', 'Si', 'Se'] as $func) {
            $this->assertGreaterThan(0, $scores[$func], "Cognitive function {$func} should be greater than 0");
        }
    }
}
