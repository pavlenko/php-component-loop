<?php

namespace PE\Component\Loop\Tests;

use PE\Component\Loop\Loop;
use PE\Component\Loop\LoopInterface;
use PE\Component\Loop\TimerInterface;
use PHPUnit\Framework\TestCase;

final class LoopTest extends TestCase
{
    public function testAddSingularTimer(): void
    {
        $callable = static function () {};

        $timer = (new Loop())->addSingularTimer(0.1, $callable);

        self::assertSame(0.1, $timer->getInterval());
        self::assertSame($callable, $timer->getCallable());
        self::assertFalse($timer->isPeriodic());
    }

    public function testAddPeriodicTimer(): void
    {
        $callable = static function () {};

        $timer = (new Loop())->addPeriodicTimer(0.1, $callable);

        self::assertSame(0.1, $timer->getInterval());
        self::assertSame($callable, $timer->getCallable());
        self::assertTrue($timer->isPeriodic());
    }

    public function testExecutePeriodicTimerUntilRemoved(): void
    {
        $executed = 0;

        $loop = new Loop();
        $loop->addPeriodicTimer(0.1, static function (LoopInterface $loop, TimerInterface $timer) use (&$executed) {
            if ($executed === 3) {
                $loop->removeTimer($timer);
                $loop->stop();
                return;
            }

            $executed++;
        });
        $loop->run();

        self::assertSame(3, $executed);
    }
}
