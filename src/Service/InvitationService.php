<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Invitation;
use App\Entity\Server;
use App\Entity\ServerMember;
use App\Entity\User;
use App\Repository\InvitationRepository;
use App\Repository\ServerMemberRepository;
use Doctrine\ORM\EntityManagerInterface;

class InvitationService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly InvitationRepository $invitationRepository,
        private readonly ServerMemberRepository $serverMemberRepository,
    ) {
    }

    public function create(Server $server, ?\DateTimeImmutable $expiresAt = null): Invitation
    {
        $invitation = (new Invitation())
            ->setServer($server)
            ->setCode(bin2hex(random_bytes(8)))
            ->setExpiresAt($expiresAt);

        $this->entityManager->persist($invitation);
        $this->entityManager->flush();

        return $invitation;
    }

    public function joinByCode(string $code, User $user): ?Server
    {
        $invitation = $this->invitationRepository->findByInviteCode($code);
        if (!$invitation instanceof Invitation || $invitation->isExpired()) {
            return null;
        }

        $server = $invitation->getServer();
        if (!$server instanceof Server) {
            return null;
        }

        $existing = $this->serverMemberRepository->findOneByServerAndUser($server, $user);
        if ($existing instanceof ServerMember) {
            return $server;
        }

        $membership = (new ServerMember())
            ->setServer($server)
            ->setUser($user)
            ->setRole(ServerMember::ROLE_MEMBER);

        $this->entityManager->persist($membership);
        $this->entityManager->flush();

        return $server;
    }
}
