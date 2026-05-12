<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Server;
use App\Entity\ServerMember;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServerMember>
 */
class ServerMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServerMember::class);
    }

    public function findOneByServerAndUser(Server $server, User $user): ?ServerMember
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.server = :server')
            ->andWhere('m.user = :user')
            ->setParameter('server', $server)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
