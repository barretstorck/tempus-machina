<?php

namespace BarretStorck\TempusMachina\Tests;

use BarretStorck\TempusMachina\UsesClockInterface;
use BarretStorck\TempusMachina\UsesClockTrait;
use BarretStorck\TempusMachina\SystemClock;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

/**
 *
 */
class UsesClockTraitTest extends TestCase
{
    public function testSystemClockIsDefault(): void
    {
        // Given
        $object = new class implements UsesClockInterface
        {
            use UsesClockTrait;
        };

        // When
        $clock = $object->getClock();

        // Then
        $this->assertInstanceOf(
            expected: SystemClock::class,
            actual: $clock,
        );
    }

    public function testSetAndGetClock(): void
    {
        // Given
        $object = new class implements UsesClockInterface
        {
            use UsesClockTrait;
        };

        $clock = new SystemClock();

        // When
        $result = $object->setClock($clock);

        // Then
        $this->assertSame(
            expected: $object,
            actual: $result,
        );

        $this->assertSame(
            expected: $clock,
            actual: $object->getClock(),
        );
    }

    public function testResetToDefault(): void
    {
        // Given
        $object = new class implements UsesClockInterface
        {
            use UsesClockTrait;
        };

        // When
        $result = $object->setClock(null);

        // Then
        $this->assertSame(
            expected: $object,
            actual: $result,
        );

        $this->assertInstanceOf(
            expected: SystemClock::class,
            actual: $object->getClock(),
        );
    }
}
