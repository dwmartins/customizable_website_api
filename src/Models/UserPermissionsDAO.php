<?php

namespace App\Models;

use App\Class\UserPermissions;
use App\Models\Database;
use PDOException;
use Exception;

class UserPermissionsDAO extends Database{
    protected $db;

    public function __construct(Database $db){
        try {
            $this->db = self::getConnection();
        } catch (PDOException $e) {
            showAlertLog("ERROR: ". $e->getMessage());
            throw $e;
        }
    }

    public static function save(UserPermissions $permission): int {
        try {
            $pdo = self::getConnection();

            $permission->setCreatedAt(date('Y-m-d H:i:s'));
            $permission->setUpdatedAt(date('Y-m-d H:i:s'));
    
            $permissionArray = $permission->toArray();
    
            $columns = [];
            $placeholders = [];
            $values = [];
    
            foreach ($permissionArray as $key => $value) {
                $columns[] = $key;
                $placeholders[] = "?";
                $values[] = $value;
            }
    
            $columns = implode(", ", $columns);
            $placeholders = implode(", ", $placeholders);
    
            $stmt = $pdo->prepare(
                "INSERT INTO user_permissions ($columns)
                VALUES ($placeholders)"
            );
    
            $stmt->execute($values);
    
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to save user permissions");
        }
    }
}