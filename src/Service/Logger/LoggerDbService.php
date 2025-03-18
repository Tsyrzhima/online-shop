<?php

namespace Service\Logger;

use Model\Logs;

class LoggerDbService implements LoggerInterface
{
    private Logs $logsModel;
    public function __construct(){
        $this->logsModel = new Logs();
    }
    public function error(\Throwable $exception)
    {
        $this->logsModel->add($exception->getMessage(),$exception->getFile(),$exception->getLine(),date('Y-m-d H:i:s'));
    }
}