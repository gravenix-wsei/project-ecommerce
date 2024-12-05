<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\CustomerApiContext;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerApiContext>
 */
class CustomerApiContextRepository extends ServiceEntityRepository
{
    public const AVAILABLE_TOKEN_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const TOKEN_LENGTH = 64;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerApiContext::class);
    }

    public function getOrCreateContextTokenForCustomer(Customer $customer): CustomerApiContext
    {
        $token = $this->createQueryBuilder('context')
            ->where('context.customer = :customer')
            ->setParameter('customer', $customer)
            ->getQuery()
            ->getOneOrNullResult();

        $entityManager = $this->getEntityManager();
        if (!$token) {
            $token = new CustomerApiContext();
            $token->setCustomer($customer)
                ->setToken(static::generateRandomToken());

            $entityManager->persist($token);
            $entityManager->flush();
        }

        return $token;
    }

    public function findByToken(string $token): ?CustomerApiContext
    {
        return $this->createQueryBuilder('context')
            ->where('context.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public static function generateRandomToken(): string
    {
        $token = '';
        $charTableLength = \strlen(static::AVAILABLE_TOKEN_CHARS)-1;
        for ($i = 0; $i < self::TOKEN_LENGTH; $i++) {
            $token .= static::AVAILABLE_TOKEN_CHARS[\rand(0, $charTableLength)];
        }

        return $token;
    }

//    /**
//     * @return CustomerApiContext[] Returns an array of CustomerApiContext objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CustomerApiContext
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
