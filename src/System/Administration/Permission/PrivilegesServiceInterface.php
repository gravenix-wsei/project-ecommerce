<?php declare(strict_types=1);

namespace App\System\Administration\Permission;

interface PrivilegesServiceInterface
{
    public const ALL_PRIVILEGES = [
        'User management' => 'ROLE_ADMIN_USERS',
        'Group management' => 'ROLE_ADMIN_GROUPS',
        'Category management' => 'ROLE_ADMIN_CATEGORIES',
        'Product management' => 'ROLE_ADMIN_PRODUCTS',
        'Promotion management' => 'ROLE_ADMIN_PROMOTIONS',
    ];
    public const ROLE_ROOT_ADMIN = 'ROLE_ROOT_ADMINISTRATOR';
    public const ROLE_ADMIN = 'ROLE_ADMINISTRATOR';

    public function hasPermission(string $permissionName): bool;
}