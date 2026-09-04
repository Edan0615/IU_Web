<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Services\GroqService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_counseling_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_send_message_api_endpoint_returns_ai_data_and_sets_cookie(): void
    {
        // Mock GroqService to return simulated AI completion
        $aiJsonResponse = json_encode([
            'message' => 'Take a deep breath. Let us analyze this step by step.',
            'dominant_function' => 'Ne',
            'rotation_target' => 'Si',
            'rotation_vector' => 'Ne -> Si',
            'in_loop' => false,
            'emotional_clarity_score' => 0.92,
            'scores' => [
                'Ti' => 8,
                'Te' => 10,
                'Fi' => 15,
                'Fe' => 12,
                'Ni' => 14,
                'Ne' => 26,
                'Si' => 7,
                'Se' => 9,
            ],
        ]);

        $groqMock = Mockery::mock(GroqService::class);
        $groqMock->shouldReceive('generateCompletion')
            ->once()
            ->andReturn($aiJsonResponse);

        $this->app->instance(GroqService::class, $groqMock);

        // Execute API call
        $response = $this->postJson('/api/chat/send', [
            'message' => 'I have too many ideas and cannot focus on studying',
        ]);

        // Assert HTTP response
        $response->assertStatus(200);
        $response->assertCookie('counseling_session_token');

        // Assert JSON structure returned to client
        $response->assertJsonStructure([
            'chat' => [
                'id',
                'session_token',
                'dominant_function',
                'in_loop',
            ],
            'latest_analysis' => [
                'primary_function',
                'scores',
            ],
            'user_message' => [
                'id',
                'role',
                'content',
            ],
            'assistant_message' => [
                'id',
                'role',
                'content',
                'analysis_metadata',
            ],
        ]);

        // Assert actual message and metadata values returned
        $response->assertJsonPath('assistant_message.content', 'Take a deep breath. Let us analyze this step by step.');
        $response->assertJsonPath('assistant_message.analysis_metadata.primary_function', 'Ne');
        $response->assertJsonPath('assistant_message.analysis_metadata.scores.Ne', 26);

        // Assert database persistence
        $this->assertDatabaseHas('chat_messages', [
            'role' => 'user',
            'content' => 'I have too many ideas and cannot focus on studying',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'role' => 'assistant',
            'content' => 'Take a deep breath. Let us analyze this step by step.',
        ]);

        $this->assertDatabaseHas('cognitive_states', [
            'primary_function' => 'Ne',
            'emotional_clarity_score' => 0.92,
        ]);
    }
}
