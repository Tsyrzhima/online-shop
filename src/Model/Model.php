<?php

namespace Model;

use PDO;

abstract class Model
{
    protected static PDO $PDO;

    public static function getPDO(): PDO
    {
        static::$PDO = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
        return static::$PDO;
    }
    abstract static protected function getTableName(): string;
}