<?php

namespace Faber\Core\Log;

use Faber\Core\Contracts\Filesystem\Filesystem;
use Faber\Core\Contracts\Log\Log as ILog;

class Log implements ILog
{
    protected string $logFileName = 'faber.log';
    protected string $logPath = '../logs';

    public function __construct(protected Filesystem $filesystem)
    {}

    public function info(string $data)
    {
        $data = date('Y-m-d H:i:s'). ' info:: ' . $data;
        $this->filesystem->disk($this->logPath)->append($this->logFileName, $data);
    }

    public function error(string $data)
    {
        $data = date('Y-m-d H:i:s'). ' error:: ' . $data;
        $this->filesystem->disk($this->logPath)->append($this->logFileName, $data);
    }

    public function warning(string $data)
    {
        $data = date('Y-m-d H:i:s'). ' warning:: ' . $data;
        $this->filesystem->disk($this->logPath)->append($this->logFileName, $data);
    }
}