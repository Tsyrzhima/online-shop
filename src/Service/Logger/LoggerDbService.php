<?php

namespace Service\Logger;

use Model\Logs;

class LoggerDbService implements LoggerInterface
{
    public function error(\Throwable $exception)
    {
        Logs::add($exception->getMessage(),$exception->getFile(),$exception->getLine(),date('Y-m-d H:i:s'));
    }
}