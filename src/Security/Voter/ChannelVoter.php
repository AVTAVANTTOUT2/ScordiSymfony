<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Channel;
use App\Entity\ServerMember;
use App\Entity\User;
use App\Repository\ServerMemberRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ChannelVoter extends Voter
{
    public const CHANNEL_VIEW = 'CHANNEL_VIEW';
    public const CHANNEL_MANAGE = 'CHANNEL_MANAGE';

    public function __construct(private readonly ServerMemberRepository $serverMemberRepository)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CHANNEL_VIEW, self::CHANNEL_MANAGE], true)
            && $subject instanceof Channel;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Channel $channel */
        $channel = $subject;
        $server = $channel->getServer();
        if ($server === null) {
            return false;
        }

        $membership = $this->serverMemberRepository->findOneByServerAndUser($server, $user);
        if (!$membership instanceof ServerMember) {
            return false;
        }

        if ($attribute === self::CHANNEL_VIEW) {
            return true;
        }

        return in_array($membership->getRole(), [ServerMember::ROLE_OWNER, ServerMember::ROLE_ADMIN], true);
    }
}
