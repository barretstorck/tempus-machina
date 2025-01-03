<?php

namespace BarretStorck\TempusMachina;

use Psr\Clock\ClockInterface;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateMalformedIntervalStringException;

/**
 *
 */
final class OffsetClock implements ClockInterface
{
    private DateInterval $offset;

    /**
     *
     */
    public function __construct(null|int|string|DateTimeInterface|ClockInterface|DateInterval $offset = null)
    {
        $this->set($offset);
    }

    /**
     *
     */
    public function set(null|int|string|DateTimeInterface|ClockInterface|DateInterval $offset = null): self
    {
        // Handle DateInterval
        if ($offset instanceof DateInterval) {
            $this->offset = $offset;
            return $this;
        }

        $now = new DateTimeImmutable();

        // Handle unix timestamp integer
        if (is_int($offset)) {
            $timestamp = (new DateTimeImmutable())->setTimestamp($offset);
            $this->offset = $now->diff($timestamp);
            return $this;
        }

        // Handle string
        if (is_string($offset)) {
            try {
                // Handle DateInterval string
                $this->offset = new DateInterval($offset);
                return $this;
            } catch (DateMalformedIntervalStringException $error) {
                // Handle DateTime string
                $timestamp = new DateTimeImmutable($offset);
                $this->offset = $now->diff($timestamp);
                return $this;
            }
        }

        // Handle DateTimeInterface
        if ($offset instanceof DateTimeInterface) {
            $this->offset = $now->diff($offset);
            return $this;
        }

        // Handle ClockInterface
        if ($offset instanceof ClockInterface) {
            $this->offset = $now->diff($offset->now());
            return $this;
        }

        // Handle null and any other possibilites
        $this->offset = new DateInterval('PT0S');
        return $this;
    }

    /**
     *
     */
    public function now(): DateTimeImmutable
    {
        $now = new DateTimeImmutable();
        return $now->add($this->offset);
    }
}
