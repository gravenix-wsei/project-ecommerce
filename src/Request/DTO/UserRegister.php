<?php declare(strict_types=1);

namespace App\Request\DTO;

use Symfony\Component\Validator\Constraints as CoreAssert;

class UserRegister
{
    #[CoreAssert\NotBlank]
    public string $email;

    #[CoreAssert\NotBlank]
    public string $password;

    #[CoreAssert\NotBlank]
    #[CoreAssert\EqualTo(propertyPath: 'password', message: 'Passwords should be identical')]
    public string $repeatPassword;

    #[CoreAssert\NotBlank]
    public string $firstName;

    #[CoreAssert\NotBlank]
    public string $lastName;

    #[CoreAssert\NotBlank]
    public string $city;

    #[CoreAssert\NotBlank]
    public string $street;

    #[CoreAssert\NotBlank]
    public string $zipcode;

    #[CoreAssert\NotBlank]
    public string $country;
}