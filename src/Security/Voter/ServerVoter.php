<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Server;
use App\Entity\ServerMember;
use App\Entity\User;
use App\Repository\ServerMemberRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ServerVoter extends Voter
{
    public const SERVER_MANAGE = 'SERVER_MANAGE';
    public const MEMBER_KICK = 'MEMBER_KICK';

    public function __construct(
        private readonly ServerMemberRepository $serverMemberRepository,
        private readonly Security $security,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::SERVER_MANAGE, self::MEMBER_KICK], true)
            && $subject instanceof Server;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var Server $server */
        $server = $subject;
        $membership = $this->serverMemberRepository->findOneByServerAndUser($server, $user);

        if (!$membership instanceof ServerMember) {
            return false;
        }

        return in_array($membership->getRole(), [ServerMember::ROLE_OWNER, ServerMember::ROLE_ADMIN], true);
    }
}
