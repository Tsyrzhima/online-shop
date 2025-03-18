<?php

namespace Service\Logger;

use Model\Logs;

class LoggerDbService implements LoggerInterface
{
    private Logs $errorModel;
    public function __construct(){
        $this->errorModel = new Logs();
    }
    public function error(\Throwable $exception)
    {
        $this->errorModel->add($exception->getMessage(),$exception->getFile(),$exception->getLine(),date('Y-m-d H:i:s'));
    }
}