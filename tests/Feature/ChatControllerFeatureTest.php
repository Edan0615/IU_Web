<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Services\GroqService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatControllerFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test history endpoint with null/missing session_token returns empty payload.
     */
    public function test_get_history_with_null_token_returns_empty_payload(): void
    {
        $response = $this->getJson('/api/chat/history');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'session_token' => null,
                'messages' => [],
                'cognitive_states' => [],
            ]);
    }

    /**
     * Test history endpoint with existing session_token returns complete chat history.
     */
    public function test_get_history_with_existing_token_returns_chat_messages(): void
    {
        $chat = Chat::create([
            'session_token' => 'test_session_token_12345',
            'title' => 'Counseling Session',
            'dominant_function' => 'Fi',
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => 'Hello counselor',
            'analysis_metadata' => ['primary_function' => 'Fi']
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'content' => 'Hello student, how can I support you today?',
            'analysis_metadata' => ['primary_function' => 'Fi']
        ]);

        $response = $this->getJson('/api/chat/history?session_token=test_session_token_12345');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'session_token' => 'test_session_token_12345',
                'dominant_function' => 'Fi',
            ])
            ->assertJsonCount(2, 'messages');
    }

    /**
     * Test validation edge case: Sending empty or missing message returns HTTP 422.
     */
    public function test_send_message_validation_fails_for_empty_message(): void
    {
        $response = $this->postJson('/api/chat/send', [
            'message' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    /**
     * Test extreme edge case: Long message near max limit (1,950 chars) handles safely.
     */
    public function test_send_message_handles_long_input_near_max_limit_safely(): void
    {
        $this->mock(GroqService::class, function ($mock) {
            $mock->shouldReceive('generateCompletion')
                ->twice()
                ->andReturn(
                    json_encode(['primary_function' => 'Te', 'scores' => ['Te' => 28], 'cognitive_reasoning' => 'Processing intense data volume.']),
                    json_encode(['dominant_function' => 'Te', 'rotation_target' => 'Ti', 'message' => 'Processed your detailed input.', 'emotional_clarity_score' => 0.8])
                );
        });

        $longMessage = str_repeat('Exam stress analysis. ', 85); // 1,870 chars

        $response = $this->postJson('/api/chat/send', [
            'message' => $longMessage,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('chat_messages', [
            'role' => 'user',
        ]);
    }

    /**
     * Test validation edge case: Message exceeding 2,000 characters limit fails with 422.
     */
    public function test_send_message_fails_when_exceeding_max_length_limit(): void
    {
        $overlimitMessage = str_repeat('A', 2001);

        $response = $this->postJson('/api/chat/send', [
            'message' => $overlimitMessage,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    /**
     * Test extreme edge case: Special characters, HTML/XSS script vectors, and Emojis.
     */
    public function test_send_message_handles_xss_scripts_and_emojis_safely(): void
    {
        $this->mock(GroqService::class, function ($mock) {
            $mock->shouldReceive('generateCompletion')
                ->twice()
                ->andReturn(
                    json_encode(['primary_function' => 'Fe', 'scores' => ['Fe' => 22], 'cognitive_reasoning' => 'Empathetic social tone.']),
                    json_encode(['dominant_function' => 'Fe', 'rotation_target' => 'Ni', 'message' => 'Safely processed special text ✨', 'emotional_clarity_score' => 0.85])
                );
        });

        $maliciousPayload = "<script>alert('XSS_ATTACK')</script> 🔥 🚀 我想要名校畢業！";

        $response = $this->postJson('/api/chat/send', [
            'message' => $maliciousPayload,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('chat_messages', [
            'role' => 'user',
            'content' => $maliciousPayload,
        ]);
    }

    /**
     * Test multi-turn continuous conversation thread session token reuse.
     */
    public function test_send_message_multi_turn_session_continuity(): void
    {
        $this->mock(GroqService::class, function ($mock) {
            $mock->shouldReceive('generateCompletion')
                ->times(4) // 2 turns x 2 calls per turn
                ->andReturn(
                    // Turn 1
                    json_encode(['primary_function' => 'Fi', 'scores' => ['Fi' => 25]]),
                    json_encode(['dominant_function' => 'Fi', 'rotation_target' => 'Te', 'message' => 'Turn 1 reply.']),
                    // Turn 2
                    json_encode(['primary_function' => 'Te', 'scores' => ['Te' => 22]]),
                    json_encode(['dominant_function' => 'Te', 'rotation_target' => 'Ti', 'message' => 'Turn 2 reply.'])
                );
        });

        // Turn 1
        $res1 = $this->postJson('/api/chat/send', ['message' => 'Turn 1 message']);
        $res1->assertStatus(200);
        $token = $res1->json('session_token');

        // Turn 2 using returned session_token
        $res2 = $this->postJson('/api/chat/send', [
            'message' => 'Turn 2 message',
            'session_token' => $token,
        ]);
        $res2->assertStatus(200);

        $chat = Chat::where('session_token', $token)->first();
        $this->assertCount(4, $chat->messages); // 2 user + 2 assistant
    }

    /**
     * Test business rule: Unauthenticated guest user is restricted to 3 messages max.
     */
    public function test_guest_user_is_restricted_to_three_messages_max(): void
    {
        $this->mock(GroqService::class, function ($mock) {
            $mock->shouldReceive('generateCompletion')
                ->times(6) // 3 turns x 2 calls per turn
                ->andReturn(
                    json_encode(['primary_function' => 'Fi', 'scores' => ['Fi' => 20]]),
                    json_encode(['dominant_function' => 'Fi', 'message' => 'Turn 1']),
                    json_encode(['primary_function' => 'Fi', 'scores' => ['Fi' => 20]]),
                    json_encode(['dominant_function' => 'Fi', 'message' => 'Turn 2']),
                    json_encode(['primary_function' => 'Fi', 'scores' => ['Fi' => 20]]),
                    json_encode(['dominant_function' => 'Fi', 'message' => 'Turn 3'])
                );
        });

        // 1st guest message -> status 200
        $r1 = $this->postJson('/api/chat/send', ['message' => 'Guest msg 1']);
        $r1->assertStatus(200);
        $token = $r1->json('session_token');

        // 2nd guest message -> status 200
        $r2 = $this->postJson('/api/chat/send', ['message' => 'Guest msg 2', 'session_token' => $token]);
        $r2->assertStatus(200);

        // 3rd guest message -> status 200
        $r3 = $this->postJson('/api/chat/send', ['message' => 'Guest msg 3', 'session_token' => $token]);
        $r3->assertStatus(200);

        // 4th guest message -> HTTP 403 Forbidden (Guest limit reached)
        $r4 = $this->postJson('/api/chat/send', ['message' => 'Guest msg 4', 'session_token' => $token]);
        $r4->assertStatus(403)
            ->assertJson([
                'status' => 'guest_limit_reached',
                'is_guest' => true,
                'guest_user_msg_count' => 3,
            ]);
    }

    /**
     * Test guest chat session claiming: Guest chat is bound to user account upon login/registration.
     */
    public function test_guest_session_claimed_upon_user_login_and_registration(): void
    {
        $guestChat = Chat::create([
            'session_token' => 'guest_trial_session_99999',
            'title' => 'Guest Trial Session',
            'dominant_function' => 'Fi',
            'user_id' => null,
        ]);

        ChatMessage::create([
            'chat_id' => $guestChat->id,
            'role' => 'user',
            'content' => 'Guest trial question',
        ]);

        $user = \App\Models\User::factory()->create([
            'email' => 'guest_claim_tester@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        // Login with guest_session_token in request payload
        $response = $this->post('/login', [
            'email' => 'guest_claim_tester@gmail.com',
            'password' => 'password123',
            'guest_session_token' => 'guest_trial_session_99999',
        ]);

        $response->assertRedirect('/home');

        // Assert that the guest chat is now bound to the logged-in user ID
        $this->assertDatabaseHas('chats', [
            'session_token' => 'guest_trial_session_99999',
            'user_id' => $user->id,
        ]);
    }
}
