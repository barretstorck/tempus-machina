<?php

namespace BarretStorck\TempusMachina\Tests;

use BarretStorck\TempusMachina\FrozenClock;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

/**
 *
 */
class FrozenClockTest extends TestCase
{
    /**
     * Scenario 01
     * Given a FrozenClock
     *   And it is given no parameters
     * When fetching it's current timestamp
     * Then a timestamp matching the system's local time when the FrozenClock
     *   was created should be returned.
     */
    public function testScenario01(): void
    {
        // Given
        $clock = new FrozenClock(); // No constructor parameters

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

    /**
     * Scenario 02
     * Given a FrozenClock
     *   And it is given an integer unix timestamp as a constructor parameter
     * When fetching it's current timestamp
     * Then a timestamp matching the unix timestamp should be returned
     */
    public function testScenario02(): void
    {
        // Given
        $unixTimestamp = 1330859167;
        $clock = new FrozenClock($unixTimestamp);

        // When
        $clockNow = $clock->now();

        $clockTimestamp = $clockNow->getTimestamp();

        // Then
        $this->assertEquals(
            expected: $unixTimestamp,
            actual: $clockTimestamp,
        );
    
    }

    /**
     * Scenario 03
     * Given a FrozenClock
     *   And it is given a string timestamp as a constructor parameter
     * When fetching it's current timestamp
     * Then a timestamp matching the string timestamp should be returned
     */
    public function testScenario03(): void
    {
        // Given
        $timestamp = new DateTimeImmutable();
        $timestamp = $timestamp->setTimestamp(1330859167);
        $stringTimestamp = $timestamp->format(DateTimeImmutable::RFC3339);
        $clock = new FrozenClock($stringTimestamp);

        // When
        $clockNow = $clock->now();

        // Then
        $this->assertEquals(
            expected: $timestamp,
            actual: $clockNow,
        );
    }
}
