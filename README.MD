## Requirements

The following versions of PHP are supported.

* PHP 7.4+

## Installation

To install, use composer:

```
php composer.phar require pe/component-loop
```

# Usage

Loop used for run some callable delayed or repeated, example:

```php
namespace PE\Component\Loop;

$loop = new Loop();

// For delayed run callable add singular timer
$loop->addSingularTimer(0.1, static function (Loop $loop, Timer $timer) {
    // Do some work delayed by 0.1 second
});

// For repeated run callable add periodic timer
$loop->addPeriodicTimer(0.5, static function (Loop $loop, Timer $timer) {
    // Do some work at each 0.5 second
});

// For stop loop execution you may add special timer
$loop->addSingularTimer(60, static function (Loop $loop) {
    $loop->stop();
});

// Run loop
$loop->run();
```
