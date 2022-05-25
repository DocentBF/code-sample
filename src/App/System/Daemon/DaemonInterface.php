<?php


namespace App\System\Daemon;


/**
 * Interface DaemonInterface
 * @package App\System\Daemon
 */
interface DaemonInterface
{
    /**
     * Run daemon
     * Main method
     *
     * @return void
     */
    public function run(): void;

    /**
     * Stop daemon
     *
     * @return void
     */
    public function stop(): void;

    /**
     * Reload daemon
     *
     * @return void
     */
    public function reload(): void;

    /**
     * Load config, re-create dependencies (loggers, cache, etc)
     *
     * @return void
     */
    public function initialize(): void;
}
