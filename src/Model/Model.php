<?php

namespace Model;

use PDO;

class Model
{
    protected PDO $PDO;

    public function __construct()
    {
        $this->PDO = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
    }
}