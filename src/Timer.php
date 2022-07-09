<?php

namespace PE\Component\Loop;

final class Timer implements TimerInterface
{
    private float $interval;

    private \Closure $callable;

    private bool $periodic;

    private float $schedule;

    /**
     * Create new timer
     *
     * @param float    $interval
     * @param \Closure $callable
     * @param bool     $periodic
     */
    public function __construct(float $interval, \Closure $callable, bool $periodic)
    {
        $this->interval = $interval;
        $this->callable = $callable;
        $this->periodic = $periodic;
    }

    /**
     * @inheritDoc
     */
    public function getInterval(): float
    {
        return $this->interval;
    }

    /**
     * @inheritDoc
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @inheritDoc
     */
    public function isPeriodic(): bool
    {
        return $this->periodic;
    }

    /**
     * @inheritDoc
     */
    public function getSchedule(): float
    {
        return $this->schedule;
    }

    /**
     * @inheritDoc
     */
    public function setSchedule(float $schedule): void
    {
        $this->schedule = $schedule;
    }
}
