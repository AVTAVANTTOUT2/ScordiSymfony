<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Channel;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class MessageService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function send(Channel $channel, User $author, string $content): Message
    {
        $message = (new Message())
            ->setChannel($channel)
            ->setAuthor($author)
            ->setContent(trim($content));

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }
}
