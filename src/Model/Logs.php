<?php

namespace Model;

class Logs extends Model
{
    public function getTableName(): string
    {
        return 'errors';
    }
    public function add(string $message, string $file, int $line, string $date)
    {
        $stmt = $this->PDO->prepare
        (
            "INSERT INTO {$this->getTableName()}(message, file, line, date)
                        VALUES (:message, :file, :line, :date)"
        );
        $stmt->execute(['message' => $message, 'file' => $file, 'line' => $line, 'date' => $date]);
    }
}