<?php

namespace Service\Logger;

interface LoggerInterface
{
    public function error(\Throwable $exception);
}