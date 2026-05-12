<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Message;
use App\Entity\ServerMember;
use App\Entity\User;
use App\Repository\ServerMemberRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MessageVoter extends Voter
{
    public const MESSAGE_DELETE_ANY = 'MESSAGE_DELETE_ANY';

    public function __construct(private readonly ServerMemberRepository $serverMemberRepository)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::MESSAGE_DELETE_ANY && $subject instanceof Message;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var Message $message */
        $message = $subject;
        $server = $message->getChannel()?->getServer();
        if ($server === null) {
            return false;
        }

        $membership = $this->serverMemberRepository->findOneByServerAndUser($server, $user);
        if (!$membership instanceof ServerMember) {
            return false;
        }

        return in_array($membership->getRole(), [ServerMember::ROLE_OWNER, ServerMember::ROLE_ADMIN], true);
    }
}
