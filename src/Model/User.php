<?php

namespace Model;

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $avatarUrl;

    public function getTableName(): string
    {
        return 'users';
    }

    public function registrate(string $name, string $email, string $hashed_password)
    {
        $statement = $this->PDO->prepare("INSERT INTO {$this->getTableName()} (name, email, password) VALUES (:name, :email, :hashed_password)");
        $statement->execute(['name' => $name, 'email' => $email, 'hashed_password' => $hashed_password]);
    }
    public function getByEmail(string $email): self|null
    {
        $statement = $this->PDO->prepare("SELECT * FROM {$this->getTableName()} WHERE email = :email");
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();
        if(!$user){
            return null;
        }
        return $this->createObj($user);
    }
    public function getById(int $userId): self|null
    {
        $statement = $this->PDO->query("SELECT * FROM {$this->getTableName()} WHERE id = $userId");
        $user = $statement->fetch();
        if(!$user){
            return null;
        }
        return $this->createObj($user);

    }
    public function updateNameById(string $name, int $userId)
    {
        $statement = $this->PDO->prepare("UPDATE {$this->getTableName()} SET name = :name WHERE id = :id");
        $statement->execute(['id' => $userId, 'name' => $name]);
    }
    public function updateEmailById(string $email, int $userId)
    {
        $statement = $this->PDO->prepare("UPDATE {$this->getTableName()} SET email = :email WHERE id = :id");
        $statement->execute(['id' => $userId, 'email' => $email]);
    }
    public function updatePasswordById(string $password, int $userId)
    {
        $statement = $this->PDO->prepare("UPDATE {$this->getTableName()} SET password = :password WHERE id = :id");
        $statement->execute(['id' => $userId, 'password' => $password]);
    }

    private function createObj(array $user): self|null
    {
        $obj = new self();
        $obj->id = $user['id'];
        $obj->name = $user['name'];
        $obj->email = $user['email'];
        $obj->password = $user['password'];
        if (isset($user['avatar_url'])) {
            $obj->avatarUrl = $user['avatar_url'];
        } else {
            $obj->avatarUrl = 'https://e7.pngegg.com/pngimages/895/969/png-clipart-national-school-of-agricultural-engineering-bordeaux-social-media-avatar-computer-icons-smooth-bending-technology-background-free-down-france-engineering.png';
        }

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
    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

}