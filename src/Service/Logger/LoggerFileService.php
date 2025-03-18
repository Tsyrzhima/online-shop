<?php

namespace Service\Logger;

class LoggerFileService implements LoggerInterface
{
    public function error(\Throwable $exception)
    {
        $error = [
            'Message: ' . $exception->getMessage(),
            'File: ' . $exception->getFile(),
            'Line: ' . $exception->getLine(),
            'Date: ' . date('Y-m-d H:i:s'),
            "\n"
        ];
        file_put_contents('../Storage/Log/errors.txt', implode("\n", $error), FILE_APPEND);
        // добавить файл в группу веб-сервера sudo chown :www-data ./src/Storage/Log/errors.txt
        // или лучше изменить владельца файла errors.txt на www-data sudo chown -R www-data ./src/Storage/Log/errors.txt
    }

}