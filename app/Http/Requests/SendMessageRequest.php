<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request validation for user counseling messages.
 * Encapsulates input validation & authorization rules per SOLID principles.
 */
class SendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool Always true (guest trial access allowed)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // User message content: Required string, max 2000 chars to prevent payload overflow
            'message' => ['required', 'string', 'max:2000'],
            // Optional unique session token string for continuous conversation session tracking
            'session_token' => ['nullable', 'string', 'max:64'],
        ];
    }
}
