<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PresenceService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function heartbeat(User $user, string $status = 'online'): void
    {
        $allowedStatuses = ['online', 'idle', 'offline'];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'online';
        }

        $user
            ->setPresenceStatus($status)
            ->setLastSeenAt(new \DateTimeImmutable());

        $this->entityManager->flush();
    }
}
