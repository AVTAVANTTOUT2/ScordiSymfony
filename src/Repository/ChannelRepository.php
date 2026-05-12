<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Channel;
use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Channel>
 */
class ChannelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Channel::class);
    }

    /**
     * @return list<Channel>
     */
    public function findByServer(Server $server): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.server = :server')
            ->setParameter('server', $server)
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
