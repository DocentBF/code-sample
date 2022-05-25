<?php

namespace App\System\Daemon;

use App\System\Log;

/**
 * Class Daemon
 * @package App\System\Daemon
 */
abstract class AbstractDaemon implements DaemonInterface
{
    /**
     * Stop daemon
     */
    public function stop(): void
    {
        Log::info(static::class . " Shutting down process");
        $this->halt();
        exit();
    }

    /**
     * Halt daemon
     */
    abstract protected function halt(): void;

    /**
     *
     */
    public function reload(): void
    {
        Log::info(static::class . " Reloading background process ");

        $this->halt();
        $this->initialize();
        $this->run();
    }

    /**
     * Init method
     */
    abstract public function initialize(): void;

    /**
     *
     */
    public function run(): void
    {
        Log::info(static::class . " Starting background process ");
    }
}
