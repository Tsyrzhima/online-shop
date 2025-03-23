<?php

namespace Model;

class Logs extends Model
{
    public static function getTableName(): string
    {
        return 'errors';
    }
    public static function add(string $message, string $file, int $line, string $date)
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare
        (
            "INSERT INTO $tableName (message, file, line, date)
                        VALUES (:message, :file, :line, :date)"
        );
        $stmt->execute(['message' => $message, 'file' => $file, 'line' => $line, 'date' => $date]);
    }
}