<?php

namespace BarretStorck\TempusMachina;

use DateTimeImmutable;
use DateTimeInterface;
use Psr\Clock\ClockInterface;

/**
 * Always provides the whatever timestamp is last given to it. This clock does
 * not change as real time progresses and is meant to be used to simulate different
 * times for testing purposes.
 */
final class FrozenClock implements ClockInterface
{
    private DateTimeImmutable $timestamp;

    /**
     *
     */
    public function __construct(null|int|string|DateTimeImmutable|ClockInterface $timestamp = null)
    {
        $this->set($timestamp);
    }

    /**
     * Allow for setting the timestamp via a wide variety of formats including:
     * - Unix timestamp (integer)
     * - DateTime formatted string (string) (https://www.php.net/manual/en/datetime.construct.php)
     * - Existing DateTimeInterface such as Carbon (DateTimeInterface)
     * - Existing ClockInterface (ClockInterface)
     * - null to use current system timestamp as default (null)
     */
    public function set(null|int|string|DateTimeInterface|ClockInterface $timestamp = null): self
    {
        // Handle unix timestamp integer
        if (is_int($timestamp)) {
            $this->timestamp = (new DateTimeImmutable())->setTimestamp($timestamp);
            return $this;
        }

        // Handle DateTime string
        if (is_string($timestamp)) {
            $this->timestamp = new DateTimeImmutable($timestamp);
            return $this;
        }

        // Handle DateTimeInterface
        if ($timestamp instanceof DateTimeInterface) {
            $this->timestamp = DateTimeImmutable::createFromInterface($timestamp);
            return $this;
        }

        // Handle ClockInterface
        if ($timestamp instanceof ClockInterface) {
            $this->timestamp = $timestamp->now();
            return $this;
        }

        // Handle null and any other possibilites
        $this->timestamp = new DateTimeImmutable();
        return $this;
    }

    public function now(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}
