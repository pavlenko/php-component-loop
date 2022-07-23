<?php

namespace PE\Component\Loop;

use Closure;

final class Loop implements LoopInterface
{
    private float $delay;

    private int $sleep;

    private ?Closure $onTick;

    private bool $running = false;

    private bool $sorted = false;

    /**
     * @var TimerInterface[]
     */
    private array $timers = [];

    /**
     * @var float[]
     */
    private array $schedule = [];

    /**
     * Creates a cycle with a certain tick precision (to regulate the load on the CPU)
     *
     * @param int $precisionMs Precision in milliseconds
     * @param Closure|null $onTick Closure to be called on each tick
     */
    public function __construct(int $precisionMs = 1, Closure $onTick = null)
    {
        $precisionMs = max($precisionMs, 1);

        $this->sleep = $precisionMs * 500;
        $this->delay = $precisionMs / 1000.0;

        $this->onTick = $onTick;
    }

    /**
     * @inheritDoc
     */
    public function addSingularTimer(float $seconds, callable $callable): TimerInterface
    {
        return $this->createTimer($seconds, $callable, false);
    }

    /**
     * @inheritDoc
     */
    public function addPeriodicTimer(float $seconds, callable $callable): TimerInterface
    {
        return $this->createTimer($seconds, $callable, true);
    }

    /**
     * Create and add timer object by params
     *
     * @param float    $seconds
     * @param callable $callable
     * @param bool     $periodic
     *
     * @return TimerInterface
     */
    private function createTimer(float $seconds, callable $callable, bool $periodic): TimerInterface
    {
        $timer = new Timer($seconds, $callable, $periodic);

        $id = spl_object_hash($timer);

        $schedule = microtime(true) + $timer->getInterval();

        $timer->setSchedule($schedule);

        $this->timers[$id]   = $timer;
        $this->schedule[$id] = $schedule;

        $this->sorted = false;

        return $timer;
    }

    /**
     * @inheritDoc
     */
    public function removeTimer(TimerInterface $timer): void
    {
        $id = spl_object_hash($timer);

        unset($this->timers[$id], $this->schedule[$id]);
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        $this->running = true;

        while ($this->running) {
            if (null !== $this->onTick) {
                call_user_func($this->onTick, $this);
            }

            if (!$this->sorted) {
                $this->sorted = true;
                asort($this->schedule);
            }

            $time = microtime(true);
            while ((microtime(true) - $time) < $this->delay) {
                usleep($this->sleep);
            }

            foreach ($this->schedule as $id => $scheduled) {
                if ($scheduled > $time) {
                    // schedule is sorted so we can safely can break
                    break;
                }

                if (!isset($this->schedule[$id]) || $this->schedule[$id] !== $scheduled) {
                    // If timer removed while we loop - skip the current schedule
                    // @codeCoverageIgnoreStart
                    continue;
                    // @codeCoverageIgnoreEnd
                }

                // Get timer for ensure it not deleted before call
                $timer = $this->timers[$id];

                call_user_func($timer->getCallable(), $this, $timer);

                // Re-schedule periodic timers and delete singular
                if (isset($this->timers[$id]) && $timer->isPeriodic()) {
                    $timer->setSchedule($timer->getSchedule() + $timer->getInterval());

                    $this->schedule[$id] = $timer->getSchedule();
                    $this->sorted        = false;
                } else {
                    unset($this->timers[$id], $this->schedule[$id]);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function stop(): void
    {
        $this->running = false;
    }
}
