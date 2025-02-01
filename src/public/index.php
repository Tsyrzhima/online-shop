<?php
$pdo = new PDO('pgsql:host=db;dbname=mydb', 'user', 'pwd');
$pdo->exec("INSERT INTO users (name, email, password) VALUES ('Maria', 'maria@mail.ru', 'maria123')");
$statement = $pdo->query('SELECT * FROM users WHERE id = 2');

$data = $statement->fetch();
echo "<pre>";
print_r($data);
echo "<pre>";