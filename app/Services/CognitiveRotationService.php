<?php

namespace App\Services;

/**
 * Psychological Cognitive Rotation and Pedagogical Prompt Generator.
 * Explicitly implements Cognitive Rotation Mapping (e.g. Student Fi -> AI Intervention Fe).
 */
class CognitiveRotationService
{
    /**
     * Map student's current dominant function or state to target Cognitive Rotation Intervention function.
     * Jungian Rotation Matrix:
     * - Fi (Introverted Feeling overload/isolation) -> Intervene with Fe (Extraverted Feeling / social validation & group harmony)
     * - Ti (Introverted Thinking loop/over-analysis) -> Intervene with Te/Se (External structure & physical grounding)
     * - Ni (Introverted Intuition future anxiety) -> Intervene with Ne/Se (Exploring present options & physical reality)
     * - Si (Introverted Sensing past failure fixation) -> Intervene with Ne (Future possibilities & reframing)
     */
    protected array $rotationMap = [
        'Fi' => ['target' => 'Fe', 'strategy' => 'Express active empathy, external emotional validation, and social harmony reassurance to pull student out of internal Fi self-blame.'],
        'Ti' => ['target' => 'Te', 'strategy' => 'Provide clear, structured, actionable exterior steps (Te) to break internal logic paralysis.'],
        'Ni' => ['target' => 'Se', 'strategy' => 'Direct attention to immediate sensory grounding (Se) to break catastrophic future anxiety.'],
        'Si' => ['target' => 'Ne', 'strategy' => 'Introduce fresh positive possibilities and alternative angles (Ne) to unlock past failure obsession.'],
        'Fe' => ['target' => 'Fi', 'strategy' => 'Guide student back to their own inner values (Fi) instead of worrying solely about pleasing others.'],
        'Te' => ['target' => 'Ti', 'strategy' => 'Encourage internal reflection and deeper conceptual understanding (Ti) rather than rushing tasks.'],
        'Ne' => ['target' => 'Ni', 'strategy' => 'Help narrow down overwhelming ideas into one focused, meaningful vision (Ni).'],
        'Se' => ['target' => 'Si', 'strategy' => 'Connect present stress to past successful routines and steady habits (Si).'],
    ];

    /**
     * Formulate system prompt specifying the exact cognitive rotation path.
     *
     * @param array $analysis Cognitive analysis payload
     * @return string Formatted system prompt instructions for LLM completion
     */
    public function getSystemPrompt(array $analysis): string
    {
        $primary = $analysis['primary_function'] ?? 'Fi';
        $loop = $analysis['loop_detected'];
        $inLoop = $analysis['in_loop'];

        $rotationInfo = $this->rotationMap[$primary] ?? $this->rotationMap['Fi'];
        $targetFunc = $rotationInfo['target'];
        $rotationStrategy = $rotationInfo['strategy'];

        $prompt = "You are a warm, human-centric counseling companion designed specifically for students under intense exam stress.\n";
        $prompt .= "YOUR CORE IDENTITY:\n";
        $prompt .= "- Informed by certified TESOL/TEFL clarity principles (clear, empathetic language).\n";
        $prompt .= "- Grounded in UCSC 'Teaching Math for Understanding' principles (scaffolding concepts step-by-step).\n";
        $prompt .= "- Psychological Framework: Carl Jung's 8 Cognitive Functions & Cognitive Rotation.\n\n";

        $prompt .= "COGNITIVE ROTATION DIRECTION:\n";
        $prompt .= "- Student Detected State: [{$primary}]\n";
        $prompt .= "- Target Rotation Function: [{$targetFunc}] (Rotation Vector: {$primary} -> {$targetFunc})\n";
        $prompt .= "- Intervention Strategy: {$rotationStrategy}\n";

        if ($inLoop) {
            $prompt .= "- URGENT LOOP INTERVENTION: Student is stuck in {$loop}. Apply {$targetFunc} rotation strongly to break this loop!\n";
        }

        $prompt .= "LANGUAGE MATCHING MANDATE:\n";
        $prompt .= "- You MUST respond in the EXACT SAME LANGUAGE as the student's latest message (e.g. if the user writes in English, reply in English; if in Traditional Chinese, reply in Traditional Chinese; if in Japanese, reply in Japanese, etc.).\n\n";
        $prompt .= "CRITICAL OUTPUT FORMAT REQUIREMENT:\n";
        $prompt .= "You MUST reply ONLY with a valid JSON object matching this exact structure (no markdown fences, no raw text outside JSON):\n";
        $prompt .= "{\n";
        $prompt .= '  "message": "Your supportive response applying Cognitive Rotation (' . $primary . ' -> ' . $targetFunc . ') written strictly in the EXACT SAME LANGUAGE as the student\'s message.",' . "\n";
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
        $prompt .= '  "rotation_vector": "' . $primary . ' -> ' . $targetFunc . '",' . "\n";
        $prompt .= '  "in_loop": ' . ($inLoop ? 'true' : 'false') . ",\n";
        $prompt .= '  "emotional_clarity_score": 0.8' . "\n";
        $prompt .= "}\n";

        return $prompt;
    }
}
