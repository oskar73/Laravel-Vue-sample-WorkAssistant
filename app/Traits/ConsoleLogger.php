<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

trait ConsoleLogger
{
    /**
     * @param      $message
     * @param null $verbosity
     */
    public function info($message, $verbosity = null)
    {
        $this->baseLog($message, 'info');
    }

    /**
     * @param      $message
     * @param null $verbosity
     */
    public function error($message, $verbosity = null)
    {
        $this->baseLog($message, 'error');
    }

    /**
     * @param      $message
     * @param null $verbosity
     */
    public function warn($message, $verbosity = null)
    {
        $this->baseLog($message, 'warn');
    }

    /**
     * @param      $message
     * @param null $verbosity
     */
    public function comment($message, $verbosity = null)
    {
        $this->baseLog($message, 'comment');
    }

    /**
     * @param        $message
     * @param string $level
     */
    private function baseLog($message, string $level = 'info')
    {
        // Perform checks before logging in
        $this->prepareForLogging();

        // Log to /logs directory
        $this->getLogger()->$level($message);

        // Log to console
        parent::$level($message);
    }

    /**
     * @throws \ErrorException
     */
    private function prepareForLogging()
    {
        if (! ($this instanceof Command)) {
            throw new \ErrorException('The logger should be used only in console commands');
        }
    }

    /**
     * @return Logger
     */
    private function getLogger()
    {
        $logger = new Logger($this->getCommandDir());
        $handler = new RotatingFileHandler($this->getLogPath(), 7);
        $handler->setFilenameFormat('{date}', RotatingFileHandler::FILE_PER_DAY);
        $logger->pushHandler($handler);

        return $logger;
    }

    /**
     * @return string
     */
    protected function getLogPath()
    {
        $directory = $this->getLogDir();
        $logName = $this->getLogName();

        $this->makeDir($directory);

        return storage_path("$directory/$logName");
    }

    /**
     * @return string
     */
    protected function getLogDir()
    {
        $commandDir = $this->getCommandDir();

        return "logs/commands/{$commandDir}";
    }

    /**
     * @return mixed
     */
    protected function getCommandDir()
    {
        return str_replace('_', '-', Str::snake(last(explode('\\', static::class))));
    }

    /**
     * @return string
     */
    protected function getLogName()
    {
        $date = Carbon::now()->format('d-m-Y');

        return "{$date}.log";
    }

    /**
     * @param string $directory
     *
     * @return bool
     */
    private function makeDir(string $directory)
    {
        return Storage::makeDirectory($directory);
    }
}
