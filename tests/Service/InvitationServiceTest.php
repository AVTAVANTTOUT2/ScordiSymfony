<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\InvitationRepository;
use App\Repository\ServerMemberRepository;
use App\Service\InvitationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class InvitationServiceTest extends TestCase
{
    public function testJoinByCodeReturnsNullWhenCodeUnknown(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $invitationRepository = $this->createMock(InvitationRepository::class);
        $serverMemberRepository = $this->createMock(ServerMemberRepository::class);
        $invitationRepository->method('findByInviteCode')->willReturn(null);
        $entityManager->expects(self::never())->method('persist');

        $service = new InvitationService($entityManager, $invitationRepository, $serverMemberRepository);
        $user = (new User())->setEmail('u@test.com')->setUsername('user')->setPassword('hash');

        self::assertNull($service->joinByCode('UNKNOWN', $user));
    }
}
