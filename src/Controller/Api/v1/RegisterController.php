<?php declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Repository\CustomerRepository;
use App\Request\DTO\UserRegister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly CustomerRepository $customerRepository
    )
    {
    }

    #[Route('/api/v1/register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] UserRegister $userRegister
    ): Response
    {
        $customer = $this->customerRepository->registerCustomer($userRegister);
        // TODO save customer in session
        return new JsonResponse(['ok' => true]);
    }
}