<?php declare(strict_types=1);

namespace App\System\Api\Behaviour;

use App\Entity\CustomerApiContext;
use App\Repository\CustomerApiContextRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ContextTokenVariableResolver implements ValueResolverInterface
{
    public const ATTRIBUTE_TOKEN = 'customer-token';

    private array $apiContextCache = [];

    public function __construct(
        private readonly CustomerApiContextRepository $customerApiContextRepository,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();
        if ($type !== CustomerApiContext::class) {
            return [];
        }

        $customerToken = $this->extractTokenFromRequest($request);
        if (!$customerToken) {
            return [];
        }

        return [$this->resolveToken($customerToken)];
    }

    public function extractTokenFromRequest(Request $request): ?string
    {
        return (string) $request->headers->get(self::ATTRIBUTE_TOKEN);
    }

    public function resolveToken(string $token): ?CustomerApiContext
    {
        if (!isset($this->apiContextCache[$token])) {
            $this->apiContextCache[$token] = $this->customerApiContextRepository->findByToken($token);
        }

        return $this->apiContextCache[$token];
    }
}