<?php

require_once '../Model/Model.php';
class User extends Model
{
    public function registrate(string $name, string $email, string $hashed_password)
    {
        $statement = $this->PDO->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :hashed_password)");
        $statement->execute(['name' => $name, 'email' => $email, 'hashed_password' => $hashed_password]);
    }
    public function getByEmail(string $email): array|false
    {
        $statement = $this->PDO->prepare("SELECT * FROM users WHERE email = :email");
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();
        return $user;
    }
    public function getById(int $userId): array|false
    {
        $statement = $this->PDO->query("SELECT * FROM users WHERE id = $userId");
        $data = $statement->fetch();
        return $data;
    }
    public function updateById(array $dataNew, string $column, int $userId)
    {
        $statement = $this->PDO->prepare("UPDATE users SET {$column} = :{$column} WHERE id = :id");
        $statement->execute(['id' => $userId, "{$column}" => $dataNew["{$column}"]]);
    }


}