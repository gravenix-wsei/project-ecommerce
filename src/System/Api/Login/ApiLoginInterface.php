<?php declare(strict_types=1);

namespace App\System\Api\Login;

use App\Entity\CustomerApiContext;

interface ApiLoginInterface
{
    public function login(string $email, string $password): CustomerApiContext;
}