<?php

namespace BarretStorck\TempusMachina\Tests;

use BarretStorck\TempusMachina\FrozenClock;
use Psr\Clock\ClockInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use DateTimeImmutable;

/**
 *
 */
class FrozenClockTest extends TestCase
{
    public static function provideTestParameters(): array
    {
        return [
            'null' => [
                'given' => null,
                'expect' => (new DateTimeImmutable())->getTimestamp(),
            ],
            'integer' => [
                'given' => 1330859167,
                'expect' => 1330859167,
            ],
            'string' => [
                'given' => '2025-01-03T15:30:35+00:00',
                'expect' => 1735918235,
            ],
            'DateTimeInterface' => [
                'given' => new DateTimeImmutable('2025-01-03T15:30:35+00:00'),
                'expect' => 1735918235,
            ],
            'ClockInterface' => [
                'given' => new class implements ClockInterface {
                    public function now(): DateTimeImmutable
                    {
                        return new DateTimeImmutable('2025-01-03T15:30:35+00:00');
                    }
                },
                'expect' => 1735918235,
            ]
        ];
    }

    #[DataProvider('provideTestParameters')]
    public function testFrozenClock($given, $expect): void
    {
        // Given
        $clock = new FrozenClock($given);

        // When
        $timestamp = $clock->now()->getTimestamp();

        // Then
        $this->assertGreaterThanOrEqual(
            expected: $expect,
            actual: $timestamp,
        );
    }

    public function testFrozenClockDoesNotMoveForward()
    {
        // Given
        $clock = new FrozenClock();

        // When
        $before = $clock->now()->getTimestamp();
        sleep(1); // Wait for a real amount of time
        $after = $clock->now()->getTimestamp();

        // Then
        $this->assertEquals(
            expected: $before,
            actual: $after,
        );
    }
}
