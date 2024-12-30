<?php

namespace BarretStorck\TempusMachina;

use Psr\Clock\ClockInterface;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;

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
        if (is_null($offset)) {
            $this->interval = new DateInterval('PT0S');
        } elseif (is_int($offset)) {
            $string = 'PT' . ((string) $offset) . 'S';
            $this->offset = new DateInterval($string);
        } elseif (is_string($offset)) {
            $this->offset = new DateInterval($offset);
        } elseif ($offset instanceof DateTimeInterface) {
            $now = new DateTimeImmutable();
            $this->offset = $now->diff($offset);
        } elseif ($offset instanceof ClockInterface) {
            $now = new DateTimeImmutable();
            $this->offset = $now->diff($offset->now());
        } elseif ($offset instanceof DateInterval) {
            $this->offset = $offset;
        }

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
