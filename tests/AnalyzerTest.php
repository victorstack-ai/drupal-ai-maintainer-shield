<?php

namespace DrupalMaintainerShield;

use PHPUnit\Framework\TestCase;
use DrupalMaintainerShield\Analyzer;

class AnalyzerTest extends TestCase {
    public function testHighSignalPatch() {
        $analyzer = new Analyzer();
        $patch = "Security-Category: XSS\nCVE-2026-1234\nFixing db_query with \$variable";
        $result = $analyzer->analyze($patch);
        
        $this->assertGreaterThan(70, $result['score']);
        $this->assertEquals('HIGH SIGNAL - PRIORITIZE', $result['recommendation']);
        $this->assertContains('References specific CVE ID.', $result['findings']);
    }

    public function testNoisePatch() {
        $analyzer = new Analyzer();
        $patch = "As an AI language model, I have improved some issues in the code.";
        $result = $analyzer->analyze($patch);
        
        $this->assertLessThan(30, $result['score']);
        $this->assertEquals('PROBABLE NOISE - LOW PRIORITY', $result['recommendation']);
    }

    public function testNeutralPatch() {
        $analyzer = new Analyzer();
        $patch = "Just a regular update to the README file.";
        $result = $analyzer->analyze($patch);
        
        $this->assertEquals(50, $result['score']);
        $this->assertEquals('NEEDS MANUAL REVIEW', $result['recommendation']);
    }
}