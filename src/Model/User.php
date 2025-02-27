<?php

namespace Model;

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $avatarUrl;

    public function registrate(string $name, string $email, string $hashed_password)
    {
        $statement = $this->PDO->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :hashed_password)");
        $statement->execute(['name' => $name, 'email' => $email, 'hashed_password' => $hashed_password]);
    }

    public function getByEmail(string $email): self|null
    {
        $statement = $this->PDO->prepare("SELECT * FROM users WHERE email = :email");
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return $this->createObj($user);
    }

    public function getById(int $userId): self|null
    {
        $statement = $this->PDO->query("SELECT * FROM users WHERE id = $userId");
        $user = $statement->fetch();

        return $this->createObj($user);

    }

    public function updateById(array $dataNew, string $column, int $userId)
    {
        $statement = $this->PDO->prepare("UPDATE users SET {$column} = :{$column} WHERE id = :id");
        $statement->execute(['id' => $userId, "{$column}" => $dataNew["{$column}"]]);
    }

    private function createObj(array $user): self|null
    {
        if(!$user){
            return null;
        }

        $obj = new self();
        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];
        $obj->avatarUrl = $user['avatar_url'];

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

}