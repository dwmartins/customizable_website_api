<?php

namespace App\Class;

use App\Models\UserRecoverPasswordDAO;

class UserRecoverPassword {
    private int $id;
    private int $user_id;
    private string $token;
    private string $used;
    private string $expiration;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(array $recover = null) {
        $this->id = $recover['id'] ?? 0;
        $this->user_id = $recover['user_id'] ?? 0;
        $this->token = $recover['token'] ?? '';
        $this->used = $recover['used'] ?? 'Y';
        $this->expiration = $recover['expiration'] ?? '';
        $this->createdAt = $recover['createdAt'] ?? '';
        $this->updatedAt = $recover['updatedAt'] ?? '';
    }

    public function toArray(): array {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'token'       => $this->token,
            'used'       => $this->used,
            'expiration' => $this->expiration,
            'createdAt'  => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void {
        $this->user_id = $user_id;
    }

    public function getToken(): string {
        return $this->token;
    }

    public function setToken(string $token): void {
        $this->token = $token;
    }

    public function getUsed(): string {
        return $this->used;
    }

    public function setUsed(string $used): void {
        $this->used = $used;
    }

    public function getExpiration(): string {
        return $this->expiration;
    }

    public function setExpiration(string $expiration): void {
        $this->expiration = $expiration;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): void {
        $this->updatedAt = $updatedAt;
    }

    public function save(): int {
        return UserRecoverPasswordDAO::save($this);
    }

    public function fetchByTokenAndUser(): array {
        $codeInfo = UserRecoverPasswordDAO::fetchByTokenAndUser($this);

        foreach ($codeInfo as $key => $value) {
            if(empty($value)) {
                continue;
            }
            
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $codeInfo;
    }

    public function update(): int {
        return UserRecoverPasswordDAO::update($this);
    }
}
