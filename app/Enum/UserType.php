<?php

namespace App\Enum;

enum UserType: string
{
    case USER = 'user';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::USER => 'Usuário',
            self::ADMIN => 'Administrador',
        };
    }
}
