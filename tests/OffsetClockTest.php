<?php

namespace BarretStorck\TempusMachina\Tests;

use BarretStorck\TempusMachina\OffsetClock;
use Psr\Clock\ClockInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use DateTimeImmutable;
use DateInterval;

/**
 *
 */
class OffsetClockTest extends TestCase
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
            'DateInterval string' => [
                'given' => 'PT0S',
                'expect' => 1735918235,
            ],
            'DateTime string' => [
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
            ],
            'DateInterval' => [
                'given' => new DateInterval('PT0S'),
                'expect' => (new DateTimeImmutable())->getTimestamp(),
            ],
        ];
    }

    #[DataProvider('provideTestParameters')]
    public function testOffsetClock($given, $expect): void
    {
        // Given
        $clock = new OffsetClock($given);

        // When
        $timestamp = $clock->now()->getTimestamp();

        // Then
        $this->assertGreaterThanOrEqual(
            expected: $expect,
            actual: $timestamp,
        );
    }

    public function testOffsetClockDoesMoveForward(): void
    {
        // Given
        $clock = new OffsetClock();

        // When
        $before = $clock->now()->getTimestamp();
        sleep(1); // Briefly pause to allow for real time to pass.
        $after = $clock->now()->getTimestamp();

        // Then
        // Assert that the second timestamp is 1 second later than the first.
        $this->assertGreaterThan(
            expected: $before,
            actual: $after,
        );
    }
}
