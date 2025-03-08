<?php

namespace Model;

use PDO;

abstract class Model
{
    protected PDO $PDO;

    public function __construct()
    {
        $this->PDO = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    }
    abstract protected function getTableName(): string;
}