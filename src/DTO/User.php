<?php

/** @noinspection PhpDocSignatureIsNotCompleteInspection */

namespace DefStudio\Telegraph\DTO;

class User
{
    private int $id;
    private bool $isBot;
    private string $firstName;
    private string $lastName;
    private string $username;

    private function __construct()
    {
    }

    /**
     * @param array{id:int, is_bot:bool, first_name:string, last_name?:string, username?:string} $data
     */
    public static function fromArray(array $data): User
    {
        $user = new self();

        $user->id = $data['id'];
        $user->isBot = $data['is_bot'];

        $user->firstName = $data['first_name'];
        $user->lastName = $data['last_name'] ?? '';
        $user->username = $data['username'] ?? '';

        return $user;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function isBot(): bool
    {
        return $this->isBot;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function username(): string
    {
        return $this->username;
    }
}
