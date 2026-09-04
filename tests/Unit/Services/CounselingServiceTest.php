<?php

namespace Tests\Unit\Services;

use App\Models\Chat;
use App\Services\CognitiveAnalysisService;
use App\Services\CognitiveRotationService;
use App\Services\CounselingService;
use App\Services\GroqService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CounselingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_counseling_service_processes_user_message_and_persists_ai_data(): void
    {
        // 1. Create a Chat session
        $chat = Chat::create([
            'session_token' => 'test-session-123',
            'title' => 'Counseling Session',
            'dominant_function' => 'Fi',
            'in_loop' => false,
        ]);

        // 2. Mock GroqService to return AI completion
        $groqMock = Mockery::mock(GroqService::class);
        $aiJsonResponse = json_encode([
            'message' => 'I understand you are feeling anxious about exams. Take a quiet breath.',
            'dominant_function' => 'Fi',
            'rotation_target' => 'Te',
            'rotation_vector' => 'Fi -> Fe -> Te',
            'in_loop' => false,
            'emotional_clarity_score' => 0.88,
            'scores' => [
                'Ti' => 5, 'Te' => 12, 'Fi' => 28, 'Fe' => 10,
                'Ni' => 18, 'Ne' => 14, 'Si' => 8, 'Se' => 6,
            ],
        ]);

        $groqMock->shouldReceive('generateCompletion')
            ->twice()
            ->andReturn($aiJsonResponse);

        $analysisService = new CognitiveAnalysisService($groqMock);
        $rotationService = new CognitiveRotationService();

        $counselingService = new CounselingService(
            $groqMock,
            $analysisService,
            $rotationService
        );

        // 3. Process user message
        $result = $counselingService->processUserMessage($chat, 'I feel overwhelmed by upcoming exams');

        // 4. Assert Database Persistence for User & Assistant Messages
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

        // 5. Assert CognitiveState was stored in DB
        $this->assertDatabaseHas('cognitive_states', [
            'chat_id' => $chat->id,
            'primary_function' => 'Fi',
            'emotional_clarity_score' => 0.88,
        ]);
    }
}
