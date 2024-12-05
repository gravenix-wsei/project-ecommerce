<?php declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Entity\CustomerApiContext;
use App\Repository\CustomerRepository;
use App\Request\DTO\UserLogin;
use App\System\Api\Login\ApiLoginInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly ApiLoginInterface $apiLogin
    ) {
    }

    #[Route('/api/v1/login', methods: ['POST'])]
    public function login(
        #[MapRequestPayload] UserLogin $userLogin
    ): JsonResponse
    {
        $context = $this->apiLogin->login($userLogin->email, $userLogin->password);

        return new JsonResponse([
            'status' => 'ok',
            'token' => $context->getToken(),
        ]);
    }

    #[Route('/api/v1/test', methods: ['POST'], defaults: ['_authCustomerRequired' => true])]
    public function test(?CustomerApiContext $context): JsonResponse
    {
        return new JsonResponse([
            'status' => 'You are authenticated',
            'email' => $context->getCustomer()?->getEmail(),
        ]);
    }
}