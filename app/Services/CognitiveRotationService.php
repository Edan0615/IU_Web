<?php

namespace App\Services;

/**
 * Psychological Cognitive Rotation and Pedagogical Prompt Generator.
 * Explicitly implements Cognitive Rotation Mapping (e.g. Student Fi -> AI Intervention Fe).
 */
class CognitiveRotationService
{
    /**
     * Two-Stage Psychological Cognitive Rotation Intervention Matrix:
     * When a student's cognitive function is overloaded:
     * 1. Bridge Function (Support/Soothe): Soothes current stress via empathetic/cognitive buffer.
     * 2. Target Function (Grounding/Goal): Provides the ultimate grounded action & mental clarity.
     *
     * Rotation Mapping Spectrum:
     * - Fi Overload -> Bridge: Fe (Empathy & Warmth) -> Target: Te (Grounded Action Steps)
     * - Ti Overload -> Bridge: Ne (Alternative Angles) -> Target: Se (Present Sensory Grounding)
     * - Ni Overload -> Bridge: Fi (Inner Value Affirmation) -> Target: Se (Present Reality)
     * - Si Overload -> Bridge: Fe (Social Warmth) -> Target: Ne (Fresh Future Possibilities)
     * - Fe Overload -> Bridge: Ni (Long-term Vision) -> Target: Fi (Authentic Self Values)
     * - Te Overload -> Bridge: Se (Pause & Deep Breath) -> Target: Ti (Deep Conceptual Logic)
     * - Ne Overload -> Bridge: Te (Structure & Priority) -> Target: Ni (Focused Core Vision)
     * - Se Overload -> Bridge: Si (Familiar Routines) -> Target: Ti (Calm Rational Analysis)
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
        $loop = $analysis['loop_detected'] ?? null;
        $inLoop = $analysis['in_loop'] ?? false;

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
        $prompt .= "- Intervention Strategy: {$rotationStrategy}\n";

        if ($inLoop) {
            $prompt .= "- URGENT LOOP INTERVENTION: Student is stuck in {$loop}. Apply {$bridgeFunc} soothing -> {$targetFunc} rotation strongly to break this loop!\n";
        }

        $prompt .= "LANGUAGE MATCHING MANDATE:\n";
        $prompt .= "- You MUST respond in the EXACT SAME LANGUAGE as the student's latest message (e.g. if the user writes in English, reply in English; if in Traditional Chinese, reply in Traditional Chinese; if in Japanese, reply in Japanese, etc.).\n\n";
        $prompt .= "CRITICAL OUTPUT FORMAT REQUIREMENT:\n";
        $prompt .= "You MUST reply ONLY with a valid JSON object matching this exact structure (no markdown fences, no raw text outside JSON):\n";
        $prompt .= "{\n";
        $prompt .= '  "message": "Your supportive response applying Two-Stage Rotation (' . $primary . ' -> ' . $bridgeFunc . ' -> ' . $targetFunc . ') written strictly in the EXACT SAME LANGUAGE as the student\'s message.",' . "\n";
        $prompt .= '  "scores": {' . "\n";
        $prompt .= '    "Ti": 0-30,' . "\n";
        $prompt .= '    "Te": 0-30,' . "\n";
        $prompt .= '    "Fi": 0-30,' . "\n";
        $prompt .= '    "Fe": 0-30,' . "\n";
        $prompt .= '    "Ni": 0-30,' . "\n";
        $prompt .= '    "Ne": 0-30,' . "\n";
        $prompt .= '    "Si": 0-30,' . "\n";
        $prompt .= '    "Se": 0-30' . "\n";
        $prompt .= "  },\n";
        $prompt .= '  "dominant_function": "' . $primary . '",' . "\n";
        $prompt .= '  "rotation_target": "' . $targetFunc . '",' . "\n";
        $prompt .= '  "rotation_vector": "' . $primary . ' -> ' . $bridgeFunc . ' -> ' . $targetFunc . '",' . "\n";
        $prompt .= '  "in_loop": ' . ($inLoop ? 'true' : 'false') . ",\n";
        $prompt .= '  "emotional_clarity_score": 0.8' . "\n";
        $prompt .= "}\n";

        return $prompt;
    }

}
