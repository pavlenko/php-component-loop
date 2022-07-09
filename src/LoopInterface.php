<?php

namespace PE\Component\Loop;

interface LoopInterface
{
    /**
     * Add a timer that will only execute once upon expiration
     *
     * @param float    $seconds
     * @param callable $callable
     *
     * @return TimerInterface
     */
    public function addSingularTimer(float $seconds, callable $callable): TimerInterface;

    /**
     * Add a timer that will run periodically
     *
     * @param float    $seconds
     * @param callable $callable
     *
     * @return TimerInterface
     */
    public function addPeriodicTimer(float $seconds, callable $callable): TimerInterface;

    /**
     * Remove previously added timer
     *
     * @param TimerInterface $timer
     */
    public function removeTimer(TimerInterface $timer): void;

    /**
     * Run loop execution
     */
    public function run(): void;

    /**
     * Stop loop execution, can be call only in timer callbacks
     */
    public function stop(): void;
}
