<?php

namespace BarretStorck\TempusMachina;

use Psr\Clock\ClockInterface;

/**
 * TODO: Use PHP 8.4's property hooks to replace get/set functions
 * https://www.php.net/manual/en/migration84.new-features.php#migration84.new-features.core.property-hooks
 */
trait UsesClockTrait
{
    private null|ClockInterface $clock;

    /**
     *
     */
    public function getClock(): ClockInterface
    {
        // If no clock was set
        // then use the device's system clock by default.
        if (!isset($this->clock)) {
            $this->clock = new SystemClock();
        }

        return $this->clock;
    }

    /**
     *
     */
    public function setClock(null|ClockInterface $clock = null): self
    {
        $this->clock = $clock;
        return $this;
    }
}
