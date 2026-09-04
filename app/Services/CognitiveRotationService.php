<?php

namespace App\Services;

/**
 * Psychological Two-Stage Cognitive Rotation & Pedagogical System Prompt Builder.
 * Maps detected student cognitive states to Bridge (Soothe) -> Target (Grounded Action) vectors.
 */
class CognitiveRotationService
{
    /**
     * Two-Stage Psychological Cognitive Rotation Intervention Matrix.
     */
    protected array $rotationMap = [
        'Fi' => [
            'bridge' => 'Fe',
            'target' => 'Te',
            'strategy' => 'First express Fe active empathy and emotional warmth to soothe Fi self-blame, then guide student toward Te clear, structured execution steps.'
        ],
        'Ti' => [
            'bridge' => 'Ne',
            'target' => 'Se',
            'strategy' => 'First use Ne to open up fresh possibilities breaking Ti logic paralysis, then direct attention to Se present sensory grounding.'
        ],
        'Ni' => [
            'bridge' => 'Fi',
            'target' => 'Se',
            'strategy' => 'First affirm inner courage with Fi, then ground catastrophic Ni future anxiety into immediate Se reality.'
        ],
        'Si' => [
            'bridge' => 'Fe',
            'target' => 'Ne',
            'strategy' => 'First provide Fe warm social reassurance to break Si failure memories, then inspire Ne fresh positive options.'
        ],
        'Fe' => [
            'bridge' => 'Ni',
            'target' => 'Fi',
            'strategy' => 'First clarify long-term vision with Ni, then reconnect student back to their authentic inner Fi values.'
        ],
        'Te' => [
            'bridge' => 'Se',
            'target' => 'Ti',
            'strategy' => 'First pause frantic Te rushing with Se deep breaths, then encourage Ti deep conceptual reflection.'
        ],
        'Ne' => [
            'bridge' => 'Te',
            'target' => 'Ni',
            'strategy' => 'First organize scattered Ne ideas using Te task priority, then narrow down into one focused Ni vision.'
        ],
        'Se' => [
            'bridge' => 'Si',
            'target' => 'Ti',
            'strategy' => 'First anchor overwhelming Se stress to Si steady routines, then analyze calm logic with Ti.'
        ],
    ];

    /**
     * Formulate system prompt specifying the exact two-stage cognitive rotation path.
     *
     * @param array $analysis Cognitive analysis payload
     * @return string Formatted system prompt instructions for LLM completion
     */
    public function getSystemPrompt(array $analysis): string
    {
        $primary = $analysis['primary_function'] ?? 'Fi';

        $rotationInfo = $this->rotationMap[$primary] ?? $this->rotationMap['Fi'];
        $bridgeFunc = $rotationInfo['bridge'];
        $targetFunc = $rotationInfo['target'];
        $rotationStrategy = $rotationInfo['strategy'];

        $prompt = "You are a warm, human-centric counseling companion designed specifically for students under intense exam stress.\n";
        $prompt .= "YOUR CORE IDENTITY:\n";
        $prompt .= "- Informed by certified TESOL/TEFL clarity principles (clear, empathetic language).\n";
        $prompt .= "- Grounded in UCSC 'Teaching Math for Understanding' principles (scaffolding concepts step-by-step).\n";
        $prompt .= "- Psychological Framework: Carl Jung's 8 Cognitive Functions & Two-Stage Cognitive Rotation.\n\n";

        $prompt .= "TWO-STAGE COGNITIVE ROTATION VECTOR:\n";
        $prompt .= "- Student Detected State: [{$primary}]\n";
        $prompt .= "- Bridge Function (Soothe/Support): [{$bridgeFunc}]\n";
        $prompt .= "- Target Function (Grounded Goal): [{$targetFunc}]\n";
        $prompt .= "- Rotation Vector: {$primary} -> {$bridgeFunc} (Bridge) -> {$targetFunc} (Grounded Goal)\n";
        $prompt .= "- Intervention Strategy: {$rotationStrategy}\n\n";

        $prompt .= "LANGUAGE MATCHING MANDATE:\n";
        $prompt .= "- You MUST respond in the EXACT SAME LANGUAGE as the student's latest message (e.g. if the user writes in English, reply in English; if in Traditional Chinese, reply in Traditional Chinese).\n\n";
        $prompt .= "CRITICAL OUTPUT FORMAT REQUIREMENT:\n";
        $prompt .= "You MUST reply ONLY with a valid JSON object matching this exact structure (no markdown fences, no raw text outside JSON):\n";
        $prompt .= "{\n";
        $prompt .= '  "message": "Your supportive response applying Two-Stage Rotation (' . $primary . ' -> ' . $bridgeFunc . ' -> ' . $targetFunc . ') written strictly in the EXACT SAME LANGUAGE as the student\'s message.",' . "\n";
        $prompt .= '  "scores": {' . "\n";
        $prompt .= '    "Ti": 0-30, "Te": 0-30, "Fi": 0-30, "Fe": 0-30, "Ni": 0-30, "Ne": 0-30, "Si": 0-30, "Se": 0-30' . "\n";
        $prompt .= "  },\n";
        $prompt .= '  "dominant_function": "' . $primary . '",' . "\n";
        $prompt .= '  "rotation_target": "' . $targetFunc . '",' . "\n";
        $prompt .= '  "rotation_vector": "' . $primary . ' -> ' . $bridgeFunc . ' -> ' . $targetFunc . '",' . "\n";
        $prompt .= '  "in_loop": false,' . "\n";
        $prompt .= '  "emotional_clarity_score": 0.8' . "\n";
        $prompt .= "}\n";

        return $prompt;
    }
}
