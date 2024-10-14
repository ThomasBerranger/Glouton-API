<?php

namespace App\Tests;

enum User: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public function getEmail(): string
    {
        return match ($this) {
            self::ADMIN => 'admin@gmail.com',
            self::USER => 'user@gmail.com',
        };
    }

    public function getRole(): array
    {
        return match ($this) {
            self::ADMIN => ['ROLE_ADMIN'],
            self::USER => ['ROLE_USER'],
        };
    }

    public function getPlainPassword(): string
    {
        return match ($this) {
            self::ADMIN => 'admin',
            self::USER => 'user',
        };
    }
}
