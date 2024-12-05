<?php declare(strict_types=1);

namespace App\System\Api\Login;

use App\Entity\Customer;
use App\Entity\CustomerApiContext;
use App\Repository\CustomerApiContextRepository;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiLoginService implements ApiLoginInterface
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly CustomerApiContextRepository $customerApiContextRepository
    )
    {
    }

    public function login(string $email, string $password): CustomerApiContext
    {
        $customer = $this->customerRepository->loginCustomer($email, $password);
        if (!$customer) {
            throw new UnauthorizedHttpException('', 'Invalid username and/or password');
        }

        return $this->customerApiContextRepository->getOrCreateContextTokenForCustomer($customer);
    }
}