<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Channel;
use App\Entity\Message;
use App\Entity\Server;
use App\Entity\ServerMember;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [];
        $rawUsers = [
            ['email' => 'admin@test.com', 'username' => 'admin_user'],
            ['email' => 'user@test.com', 'username' => 'user_one'],
            ['email' => 'alice@test.com', 'username' => 'alice_dev'],
            ['email' => 'bob@test.com', 'username' => 'bob_ops'],
            ['email' => 'carol@test.com', 'username' => 'carol_pm'],
        ];

        foreach ($rawUsers as $rawUser) {
            $user = (new User())
                ->setEmail($rawUser['email'])
                ->setUsername($rawUser['username'])
                ->setPresenceStatus('online');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();

        for ($i = 1; $i <= 3; ++$i) {
            $server = (new Server())
                ->setName('Serveur '.$i)
                ->setOwner($users[0]);
            $manager->persist($server);

            $ownerMembership = (new ServerMember())
                ->setServer($server)
                ->setUser($users[0])
                ->setRole(ServerMember::ROLE_OWNER);
            $manager->persist($ownerMembership);

            $member = (new ServerMember())
                ->setServer($server)
                ->setUser($users[$i])
                ->setRole(ServerMember::ROLE_MEMBER);
            $manager->persist($member);

            $general = (new Channel())
                ->setName('general')
                ->setType(Channel::TYPE_TEXT)
                ->setServer($server)
                ->setPosition(0);
            $manager->persist($general);

            for ($j = 1; $j <= 10; ++$j) {
                $message = (new Message())
                    ->setAuthor($users[$j % count($users)])
                    ->setChannel($general)
                    ->setContent('Message fixture #'.$j.' du serveur '.$i);
                $manager->persist($message);
            }
        }

        $manager->flush();
    }
}
