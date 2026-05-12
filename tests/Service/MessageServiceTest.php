<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Channel;
use App\Entity\User;
use App\Service\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class MessageServiceTest extends TestCase
{
    public function testSendPersistsMessage(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager->expects(self::once())->method('persist');
        $entityManager->expects(self::once())->method('flush');

        $channel = (new Channel())->setName('general');
        $user = (new User())->setEmail('u@test.com')->setUsername('user')->setPassword('hash');
        $service = new MessageService($entityManager);
        $message = $service->send($channel, $user, ' hello ');

        self::assertSame('hello', $message->getContent());
    }
}
