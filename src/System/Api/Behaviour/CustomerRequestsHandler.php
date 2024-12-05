<?php declare(strict_types=1);

namespace App\System\Api\Behaviour;

use App\Entity\CustomerApiContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class CustomerRequestsHandler implements EventSubscriberInterface
{
    public const AUTH_CUSTOMER_REQUIRED_ATTRIBUTE = '_authCustomerRequired';

    public function __construct(
        private readonly ContextTokenVariableResolver $tokenVariableResolver
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        if (!$requestEvent->isMainRequest()) {
            return;
        }

        $request = $requestEvent->getRequest();
        if (!$request->attributes->get(self::AUTH_CUSTOMER_REQUIRED_ATTRIBUTE, false)) {
            return;
        }

        $token = $this->tokenVariableResolver->extractTokenFromRequest($request);
        if (!$token || \is_null($this->tokenVariableResolver->resolveToken($token))) {
            throw new UnauthorizedHttpException('', 'You must be logged in');
        }
    }
}