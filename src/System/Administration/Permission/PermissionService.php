<?php declare(strict_types=1);

namespace App\System\Administration\Permission;

use App\Entity\AdministrationUser;
use Symfony\Bundle\SecurityBundle\Security;

class PermissionService implements PrivilegesServiceInterface
{
    public function __construct(
        private readonly Security $security
    ) {
    }

    public function hasPermission(string $permissionName): bool
    {
        if (!\in_array($permissionName, PrivilegesServiceInterface::ALL_PRIVILEGES)) {
            throw new \RuntimeException('Unknown permission used in template');
        }
        $isAdmin = $this->security->getUser()?->isAdmin() ?? false;

        return $isAdmin || $this->security->isGranted($permissionName);
    }
}