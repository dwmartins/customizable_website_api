<?php

namespace App\Models;

use App\Class\UserRecoverPassword;
use App\Models\Database;
use PDOException;
use Exception;
use PDO;

class UserRecoverPasswordDAO extends Database{
    public static function save(UserRecoverPassword $userRecoverPassword) {
        try {
            $pdo = self::getConnection();
            $userRecoverPassword->setCreatedAt(date('Y-m-d H:i:s'));
            $userRecoverPassword->setUpdatedAt(date('Y-m-d H:i:s'));
            $recoverArray = $userRecoverPassword->toArray();

            $columns = [];
            $placeholders = [];
            $values = [];

            foreach ($recoverArray as $key => $value) {
                $columns[] = $key;
                $placeholders[] = "?";
                $values[] = $value;
            }

            $columns = implode(", ", $columns);
            $placeholders = implode(", ", $placeholders);

            $stmt = $pdo->prepare(
                "INSERT INTO user_recover_password ($columns)
                VALUES ($placeholders)"
            );
            
            $stmt->execute($values);

            return $pdo->lastInsertId();

        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to save password recovery token");
        }
    }

    public static function update(UserRecoverPassword $userRecoverPassword) {
        try {
            $pdo = self::getConnection();
            $userRecoverPassword->setUpdatedAt(date('Y-m-d H:i:s'));

            $values = [
                $userRecoverPassword->getUsed(),
                $userRecoverPassword->getUpdatedAt(),
                $userRecoverPassword->getId()
            ];

            $stmt = $pdo->prepare(
                "UPDATE user_recover_password 
                SET used = ?, updatedAt = ?
                WHERE id = ?"
            );

            $stmt->execute($values);
            return $stmt->rowCount();

        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to update password recovery token");
        }
    }

    public static function fetchByTokenAndUser(UserRecoverPassword $userRecoverPassword) {
        try {
            $pdo = self::getConnection();

            $stmt = $pdo->prepare(
                "SELECT * FROM user_recover_password WHERE user_id = ? AND token = ? AND used = ? LIMIT 1"
            );

            $stmt->execute([
                $userRecoverPassword->getUserId(),
                $userRecoverPassword->getToken(),
                "N"
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: [];
        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to search password recovery token");
        }
    }
}
