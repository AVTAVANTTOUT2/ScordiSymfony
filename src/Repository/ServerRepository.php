<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Server;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Server>
 */
class ServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Server::class);
    }

    /**
     * @return list<Server>
     */
    public function findForUser(User $user): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.members', 'm')
            ->andWhere('m.user = :user')
            ->setParameter('user', $user)
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
