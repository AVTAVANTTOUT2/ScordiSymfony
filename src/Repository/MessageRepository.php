<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Channel;
use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @return list<Message>
     */
    public function findRecentInChannel(Channel $channel, int $limit = 30): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.channel = :channel')
            ->setParameter('channel', $channel)
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return list<Message>
     */
    public function findSince(Channel $channel, int $sinceTimestamp): array
    {
        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.channel = :channel')
            ->setParameter('channel', $channel)
            ->orderBy('m.createdAt', 'ASC');

        if ($sinceTimestamp > 0) {
            $sinceDate = (new \DateTimeImmutable())->setTimestamp($sinceTimestamp);
            $qb->andWhere('m.createdAt > :since')->setParameter('since', $sinceDate);
        }

        return $qb
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return list<Message>
     */
    public function findOlderThan(Channel $channel, ?int $beforeMessageId, int $limit = 30): array
    {
        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.channel = :channel')
            ->setParameter('channel', $channel)
            ->orderBy('m.id', 'DESC')
            ->setMaxResults($limit);

        if ($beforeMessageId !== null) {
            $qb->andWhere('m.id < :beforeId')->setParameter('beforeId', $beforeMessageId);
        }

        return $qb->getQuery()->getResult();
    }
}
