<?php declare(strict_types=1);

namespace App\Request\DTO;

use Symfony\Component\Validator\Constraints as CoreAssert;

class UserLogin
{
    #[CoreAssert\NotBlank]
    public string $email;

    #[CoreAssert\NotBlank]
    public string $password;
}