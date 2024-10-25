<?php

namespace App\Tests;

enum User: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case USER2 = 'user2';

    public function getEmail(): string
    {
        return match ($this) {
            self::ADMIN => 'admin@gmail.com',
            self::USER => 'user@gmail.com',
            self::USER2 => 'user2@gmail.com',
        };
    }

    public function getRole(): array
    {
        return match ($this) {
            self::ADMIN => ['ROLE_ADMIN'],
            self::USER => ['ROLE_USER'],
            self::USER2 => ['ROLE_USER'],
        };
    }

    public function getPlainPassword(): string
    {
        return match ($this) {
            self::ADMIN => 'admin',
            self::USER => 'user',
            self::USER2 => 'user2',
        };
    }
}
