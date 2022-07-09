<?php

namespace PE\Component\Loop;

interface TimerInterface
{
    /**
     * Get timer execution interval
     *
     * @return float
     */
    public function getInterval(): float;

    /**
     * A callable to be executed when the timer expires
     *
     * @return callable
     */
    public function getCallable(): callable;

    /**
     * Flag indicates that timer is periodic or not
     *
     * @return bool
     */
    public function isPeriodic(): bool;

    /**
     * Get timer next run timestamp
     *
     * @return float
     */
    public function getSchedule(): float;

    /**
     * Set timer next run timestamp
     *
     * @param float $schedule
     */
    public function setSchedule(float $schedule): void;
}
