<?php

namespace BarretStorck\TempusMachina\Tests;

use BarretStorck\TempusMachina\SystemClock;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

/**
 *
 */
class SystemClockTest extends TestCase
{
    /**
     * Scenario 01: The SystemClock should alway return the local device's
     * system time.
     * 
     * Due to the timestamps being fetched at slightly different times we should
     * expect a small difference between the system timestamp and the clock
     * timestamp, so we use "greater than or equal" and "less than or equal"
     * with a 1 second buffer to assert that it is within an acceptable time
     * range.
     */
    public function testScenario01(): void
    {
        // Given
        $clock = new SystemClock();

        // When
        $systemNow = new DateTimeImmutable();
        $clockNow = $clock->now();

        $systemTimestamp = $systemNow->getTimestamp();
        $clockTimestamp = $clockNow->getTimestamp();

        // Then
        $this->assertGreaterThanOrEqual(
            expected: $systemTimestamp,
            actual: $clockTimestamp,
        );
        
        $this->assertLessThanOrEqual(
            expected: $systemTimestamp + 1,
            actual: $clockTimestamp,
        );
    }
}
