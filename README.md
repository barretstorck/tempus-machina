      _____                                __  __            _     _             
     |_   _|__ _ __ ___  _ __  _   _ ___  |  \/  | __ _  ___| |__ (_)_ __   __ _ 
       | |/ _ \ '_ ` _ \| '_ \| | | / __| | |\/| |/ _` |/ __| '_ \| | '_ \ / _` |
       | |  __/ | | | | | |_) | |_| \__ \ | |  | | (_| | (__| | | | | | | | (_| |
       |_|\___|_| |_| |_| .__/ \__,_|___/ |_|  |_|\__,_|\___|_| |_|_|_| |_|\__,_|
                        |_|                                                      

     (Latin for Time Machine)

**Tempus Machina** is a [PSR-20](https://www.php-fig.org/psr/psr-20/) compliant
Clock library. It's purpose is to allow developers to treat time as a dependency
that can be passed into code, and therefore mocked for testing, instead of
relying on hard coded calls to `time()` and similar functions.

Have you ever wanted to make sure your code runs as expected during a daylight
savings time transition? What about if the date is February 29th? Or what if
there is a [leap second](https://en.wikipedia.org/wiki/Leap_second)?
With Tempus Machina you can simulate these scenarios in your test environments.

View on [Packagist.org](https://packagist.org/packages/barretstorck/tempus-machina).

# Setup
To add Tempus Machina to your project run:
```shell
composer require barretstorck/tempus-machina
```

# Available Clocks
There are 3 Clocks that implement `Psr\Clock\ClockInterface` and are packaged
with Tempus Machina:

### 1. SystemClock
The SystemClock is the default clock and always returns the device's real system
timestamp. Anything that uses the `UsesClockTrait` trait will automatically use
the SystemClock by default if no other clocks are given.

```php
$clock = new \BarretStorck\TempusMachina\SystemClock();
$now = $clock->now(); // Returns a DateTimeImmutable object for the system's current time.
$timestamp = $now->getTimestamp(); // Returns the system's current Unix timestamp.
```

### 2. FrozenClock
The FrozenClock will always provide whatever timestamp it is last given and will
not move forward in time. If no timestamp is given then the system's current
time will be used by default.

The FrozenClock constructor and `set()` function can accept any of the following
parameters:
- An integer unix timestamp
- A [DateTime formatted](https://www.php.net/manual/en/datetime.construct.php) string 
- An existing DateTimeInterface object
- An existing ClockInterface object
- null or no parameters to use the current system timestamp as a default

```php
// Simulate February 29th 2024.
$clock = new \BarretStorck\TempusMachina\FrozenClock('February 29th 2024');

$now1 = $clock->now();
sleep(10); // Wait 10 seconds in real time.
$now2 = $clock->now(); // $now2 is still equal to $now1.

// Simulate February 29th 2124.
$clock->set(4864860000);
```

### 3. OffsetClock
The OffsetClock will continue to move forward in real time from whatever
timestamp it is last given. If no timestamp is given then the system's current
time will be used by default.

The OffsetClock constructor and `set()` function can accept any of the following
parameters:
- An integer unix timestamp
- A [DateInterval formatted](https://www.php.net/manual/en/dateinterval.construct.php) string
- A [DateTime formatted](https://www.php.net/manual/en/datetime.construct.php) string 
- An existing DateTimeInterface object
- An existing ClockInterface object
- An existing DateInterval object
- null or no parameters to use the current system timestamp as a default

```php
// Simulate March 9th 2025 at 23:59:59 just before daylight savings time begins
$clock = new \BarretStorck\TempusMachina\OffsetClock('2025-03-25T23:59:59+00:00');

echo $clock->now()->format(\DateTimeInterface::RFC3339); // Will echo "2025-03-25T23:59:59+00:00"
sleep(10); // Wait 10 seconds to allow for real time to pass.
echo $clock->now()->format(\DateTimeInterface::RFC3339); // Will echo "2025-03-26T01:09:00+00:00"
```

# Example code
```php
use Psr\Clock\ClockInterface;
use BarretStorck\TempusMachina\{UsesClockInterface, UsesClockTrait};

class MyObject implements UsesClockInterface
{
    use UsesClockTrait;

    public function __construct(null|ClockInterface $clock = null)
    {
        // If $clock is null, then the real time SystemClock will be available
        // by default for any future calls of `getClock()`.
        $this->setClock($clock);
    }

    public function doSomething()
    {
        // No longer hard coding `time()` or `new DateTimeImmutable('now')` calls.
        //$now = time();
        //$now = new DateTimeImmutable('now');

        // The clock is now available and can be mocked in testing by providing
        // a FrozenClock or OffsetClock to the MyObject constructor or it's
        // `setClock()` function.
        $now = $this->getClock()->now();
    }
}
```
