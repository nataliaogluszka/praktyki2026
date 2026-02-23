<?php
namespace App\Enums;

class UserRole
{
    const ADMIN = 'admin';
    const USER = 'user';
    const INVENTORY = 'inventory';

    const TYPES = [
        self::ADMIN,
        self::USER,
        self::INVENTORY
    ];
}