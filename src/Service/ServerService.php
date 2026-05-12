<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Channel;
use App\Entity\Server;
use App\Entity\ServerMember;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ServerService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function create(string $name, User $owner): Server
    {
        $server = (new Server())
            ->setName($name)
            ->setOwner($owner);

        $membership = (new ServerMember())
            ->setServer($server)
            ->setUser($owner)
            ->setRole(ServerMember::ROLE_OWNER);

        $general = (new Channel())
            ->setName('general')
            ->setType(Channel::TYPE_TEXT)
            ->setServer($server)
            ->setPosition(0);

        $this->entityManager->persist($server);
        $this->entityManager->persist($membership);
        $this->entityManager->persist($general);
        $this->entityManager->flush();

        return $server;
    }
}
