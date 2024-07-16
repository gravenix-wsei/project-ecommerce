<?php declare(strict_types=1);

namespace App\System\Administration\Permission\Twig;

use App\System\Administration\Permission\PrivilegesServiceInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PermissionTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly PrivilegesServiceInterface $permissionService
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ecommerceHasPermission', [$this, 'ecommerceHasPermission']),
        ];
    }

    public function ecommerceHasPermission(string $permissionName): bool
    {
        return $this->permissionService->hasPermission($permissionName);
    }
}