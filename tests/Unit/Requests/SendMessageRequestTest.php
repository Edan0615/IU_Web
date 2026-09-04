<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\Chat\SendMessageRequest;
use Tests\TestCase;

class SendMessageRequestTest extends TestCase
{
    public function test_send_message_request_authorization_returns_true(): void
    {
        $request = new SendMessageRequest();
        $this->assertTrue($request->authorize());
    }

    public function test_send_message_request_contains_expected_validation_rules(): void
    {
        $request = new SendMessageRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('message', $rules);
        $this->assertArrayHasKey('session_token', $rules);
        $this->assertEquals('required|string|max:2000', $rules['message']);
        $this->assertEquals('nullable|string|max:64', $rules['session_token']);
    }
}
