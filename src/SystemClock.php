<?php

namespace BarretStorck\TempusMachina;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

/**
 * Always provides the local device's system time. This is mean for use with
 * production environments and acts as the default clock for anything
 * using UsesClockTrait.
 */
final class SystemClock implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
