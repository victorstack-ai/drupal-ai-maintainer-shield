<?php

namespace DrupalMaintainerShield;

class Analyzer {
    /**
     * Analyzes a patch string for security signal vs noise.
     */
    public function analyze(string $patch): array {
        $score = 50; // Neutral starting point
        $findings = [];
        $noiseSignals = 0;
        $qualitySignals = 0;

        // Heuristic: Check for common AI "noise" patterns
        if (str_contains($patch, 'As an AI language model')) {
            $score -= 40;
            $findings[] = "Direct AI boilerplate detected.";
            $noiseSignals++;
        }

        if (preg_match("/(I have|I've) (fixed|improved) (the|some) issues/", $patch)) {
            $score -= 10;
            $findings[] = "Vague description typical of low-effort AI reports.";
            $noiseSignals++;
        }

        // Heuristic: Quality signals
        if (str_contains($patch, 'Security-Category:')) {
            $score += 20;
            $findings[] = "Uses structured security metadata.";
            $qualitySignals++;
        }

        if (str_contains($patch, 'CVE-')) {
            $score += 20;
            $findings[] = "References specific CVE ID.";
            $qualitySignals++;
        }

        // Search for specific Drupal security anti-patterns
        if (str_contains($patch, 'db_query') || str_contains($patch, '->query(')) {
            if (preg_match('/\$\w+/', $patch)) {
                $score += 15;
                $findings[] = "Potential SQL injection fix detected (high signal).";
                $qualitySignals++;
            }
        }

        // Final recommendation
        $recommendation = "NEEDS MANUAL REVIEW";
        if ($score > 70) {
            $recommendation = "HIGH SIGNAL - PRIORITIZE";
        } elseif ($score < 30) {
            $recommendation = "PROBABLE NOISE - LOW PRIORITY";
        }

        return [
            'score' => max(0, min(100, $score)),
            'findings' => $findings,
            'recommendation' => $recommendation,
            'noise_signals' => $noiseSignals,
            'quality_signals' => $qualitySignals,
        ];
    }
}