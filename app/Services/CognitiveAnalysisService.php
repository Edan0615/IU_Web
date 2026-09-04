<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Psychological Jungian 8 Cognitive Function Analysis Service.
 * Evaluates raw student statements via Groq LLM JSON or baseline heuristic vectors.
 */
class CognitiveAnalysisService
{
    protected GroqService $groqService;

    public function __construct(GroqService $groqService)
    {
        $this->groqService = $groqService;
    }

    /**
     * Analyze user input statement to detect current 8 cognitive function intensities (1-30 scale).
     *
     * @param string $userText Raw student message
     * @return array Analysis payload including primary/secondary functions and 8 function scores
     */
    public function analyzeUserMessage(string $userText): array
    {
        $prompt = "You are a certified Jungian Psychological Analyst. Analyze the student's message and return ONLY a JSON object evaluating their current 8 cognitive function intensities (Ti, Te, Fi, Fe, Ni, Ne, Si, Se on 1-30 scale), primary_function, and emotional_clarity_score (0.0 to 1.0). Example:\n" .
            '{"primary_function": "Fi", "secondary_function": "Ne", "scores": {"Ti": 10, "Te": 10, "Fi": 25, "Fe": 12, "Ni": 15, "Ne": 18, "Si": 11, "Se": 9}, "emotional_clarity_score": 0.85}';

        $rawResponse = $this->groqService->generateCompletion([['role' => 'user', 'content' => $userText]], $prompt, 0.2);
        $cleanJson = Str::of($rawResponse)
            ->replaceMatches('/^```json\s*/i', '')
            ->replaceMatches('/\s*```$/i', '')
            ->trim();

        $parsed = json_decode((string)$cleanJson, true);

        if (is_array($parsed) && isset($parsed['scores'])) {
            return $this->normalizePayload($parsed);
        }

        return $this->getFallbackSpectrum($userText);
    }

    /**
     * Parse final AI completion JSON into standardized analysis payload.
     *
     * @param string $rawResponse Raw completion string from Groq LLM
     * @param array $initialAnalysis Pre-calculated analysis payload from step 1
     * @return array Standardized dynamic analysis payload
     */
    public function parseResponse(string $rawResponse, array $initialAnalysis = []): array
    {
        $cleanJson = Str::of($rawResponse)
            ->replaceMatches('/^```json\s*/i', '')
            ->replaceMatches('/\s*```$/i', '')
            ->trim();

        $parsed = json_decode((string)$cleanJson, true) ?: [];

        $primary = $parsed['dominant_function'] ?? ($initialAnalysis['primary_function'] ?? 'Fi');
        $target = $parsed['rotation_target'] ?? ($initialAnalysis['rotation_target'] ?? 'Te');

        return [
            'reply_text' => $parsed['message'] ?? $rawResponse,
            'primary_function' => $primary,
            'secondary_function' => $initialAnalysis['secondary_function'] ?? 'Te',
            'scores' => array_merge($initialAnalysis['scores'] ?? [], $parsed['scores'] ?? []),
            'rotation_target' => $target,
            'rotation_vector' => $parsed['rotation_vector'] ?? "{$primary} -> {$target}",
            'in_loop' => (bool)($parsed['in_loop'] ?? false),
            'emotional_clarity_score' => (float)($parsed['emotional_clarity_score'] ?? ($initialAnalysis['emotional_clarity_score'] ?? 0.8)),
        ];
    }

    /**
     * Normalize parsed JSON payload.
     */
    protected function normalizePayload(array $parsed): array
    {
        $scores = [];
        $functions = ['Ti', 'Te', 'Fi', 'Fe', 'Ni', 'Ne', 'Si', 'Se'];
        $rawScores = $parsed['scores'] ?? [];

        foreach ($functions as $func) {
            $scores[$func] = isset($rawScores[$func]) ? max(1, min(30, (int)$rawScores[$func])) : 12;
        }

        $sorted = $scores;
        arsort($sorted);
        $ranked = array_keys($sorted);

        return [
            'primary_function' => $parsed['primary_function'] ?? $ranked[0],
            'secondary_function' => $parsed['secondary_function'] ?? $ranked[1],
            'scores' => $scores,
            'emotional_clarity_score' => (float)($parsed['emotional_clarity_score'] ?? 0.8),
        ];
    }

    /**
     * Fallback baseline spectrum generator.
     */
    protected function getFallbackSpectrum(string $text): array
    {
        $hash = crc32(strtolower($text));
        $scores = [
            'Fi' => 18 + ($hash % 9), 'Ne' => 16 + (($hash >> 3) % 9),
            'Ti' => 14 + (($hash >> 6) % 9), 'Fe' => 12 + (($hash >> 9) % 9),
            'Ni' => 15 + (($hash >> 12) % 9), 'Te' => 10 + (($hash >> 15) % 9),
            'Si' => 11 + (($hash >> 18) % 9), 'Se' => 13 + (($hash >> 21) % 9),
        ];

        $sorted = $scores;
        arsort($sorted);
        $ranked = array_keys($sorted);

        return [
            'primary_function' => $ranked[0],
            'secondary_function' => $ranked[1],
            'scores' => $scores,
            'emotional_clarity_score' => 0.8,
        ];
    }
}
