<?php

namespace app\models;

class EmployeeRoles
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_EMPLOYEE = 'employee';
    
    public static function getRolesList(): array
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_EMPLOYEE => 'Employee',
        ];
    }
} 