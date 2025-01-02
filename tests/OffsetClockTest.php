<?php

namespace BarretStorck\TempusMachina\Tests;

use BarretStorck\TempusMachina\OffsetClock;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

/**
 *
 */
class OffsetClockTest extends TestCase
{
    /**
     * Scenario 01
     * 
     * Given an OffsetClock
     * When fetching multiple timestamps
     * Then each timestamp should reflect moving forward in real time
     */
    public function testScenario01(): void
    {
        // Given
        $clock = new OffsetClock();

        // When
        $now1 = $clock->now()->getTimestamp();
        sleep(1); // Briefly pause to allow for real time to pass.
        $now2 = $clock->now()->getTimestamp();

        // Then
        // Assert that the second timestamp is 1 second later than the first.
        $this->assertEquals(
            expected: $now1 + 1,
            actual: $now2,
        );
    }
}
