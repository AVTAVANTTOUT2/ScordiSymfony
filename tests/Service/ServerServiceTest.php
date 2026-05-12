<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\ServerService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ServerServiceTest extends TestCase
{
    public function testCreatePersistsEntities(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::exactly(3))->method('persist');
        $entityManager->expects(self::once())->method('flush');

        $owner = (new User())->setEmail('owner@test.com')->setUsername('owner_user')->setPassword('hashed');
        $service = new ServerService($entityManager);
        $server = $service->create('Mon serveur', $owner);

        self::assertSame('Mon serveur', $server->getName());
        self::assertSame($owner, $server->getOwner());
    }
}
