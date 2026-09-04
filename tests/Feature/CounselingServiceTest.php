<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\CognitiveState;
use App\Services\CounselingService;
use App\Services\GroqService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounselingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_counseling_service_can_get_or_create_chat_session(): void
    {
        /** @var CounselingService $service */
        $service = app(CounselingService::class);

        $chat = $service->getOrCreateChat(null);

        $this->assertInstanceOf(Chat::class, $chat);
        $this->assertNotEmpty($chat->session_token);
        $this->assertEquals('Counseling Session', $chat->title);
    }

    public function test_chat_send_api_endpoint_processes_message_successfully(): void
    {
        // Mock GroqService to handle 2-step completion (1st analysis, 2nd response generation)
        $this->mock(GroqService::class, function ($mock) {
            $mock->shouldReceive('generateCompletion')
                ->twice()
                ->andReturn(
                    // 1st call: Cognitive analysis output
                    json_encode([
                        'primary_function' => 'Fi',
                        'secondary_function' => 'Ne',
                        'scores' => ['Ti' => 10, 'Te' => 10, 'Fi' => 25, 'Fe' => 12, 'Ni' => 15, 'Ne' => 18, 'Si' => 11, 'Se' => 9],
                        'emotional_clarity_score' => 0.85
                    ]),
                    // 2nd call: Main response completion
                    json_encode([
                        'dominant_function' => 'Fi',
                        'rotation_target' => 'Te',
                        'message' => '非常理解您目前的感受，這完全是正常的內在反思過程。',
                        'emotional_clarity_score' => 0.85
                    ])
                );
        });


        $response = $this->postJson('/api/chat/send', [
            'message' => '我最近對於未來的職涯發展感到有些迷惘與焦慮。',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'session_token',
                'data' => [
                    'chat',
                    'latest_analysis',
                    'user_message',
                    'assistant_message',
                ]
            ]);

        $this->assertDatabaseHas('chat_messages', [
            'role' => 'user',
            'content' => '我最近對於未來的職涯發展感到有些迷惘與焦慮。',
        ]);

        $this->assertDatabaseHas('cognitive_states', [
            'primary_function' => 'Fi',
        ]);
    }
}
