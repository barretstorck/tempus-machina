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
    public function testSystemClockGivesDeviceTime(): void
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
