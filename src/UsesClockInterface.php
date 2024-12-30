<?php

namespace BarretStorck\TempusMachina;

use Psr\Clock\ClockInterface;

/**
 * TODO: Use PHP 8.4's property hooks to replace get/set functions
 * https://www.php.net/manual/en/migration84.new-features.php#migration84.new-features.core.property-hooks
 */
interface UsesClockInterface
{
    public function getClock(): ClockInterface;
    
    public function setClock(null|ClockInterface $clock = null): UsesClockInterface;
}
