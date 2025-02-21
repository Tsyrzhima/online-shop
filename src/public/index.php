<?php

use Core\App;

$autoload = function (string $className)
{
    $path = str_replace('\\', '/', $className);
    $path = "./../$path.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};

spl_autoload_register($autoload);

    $app = new App();
    $app->run();