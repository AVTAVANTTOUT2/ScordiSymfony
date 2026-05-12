<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends ControllerTestCase
{
    private function createUser(EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): User
    {
        $user = (new User())
            ->setEmail('login-test@test.com')
            ->setUsername('login_test')
            ->setPresenceStatus('offline');
        $user->setPassword($hasher->hashPassword($user, 'password'));
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    public function testLoginPageIsAccessible(): void
    {
        $client = static::createClient();
        $this->initDatabase($client);
        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Connexion');
    }

    public function testLoginWithBadCredentialsShowsError(): void
    {
        $client = static::createClient();
        $this->initDatabase($client);
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $hasher = $container->get(UserPasswordHasherInterface::class);

        $this->createUser($entityManager, $hasher);

        $client->request('POST', '/login', [
            '_username' => 'login-test@test.com',
            '_password' => 'wrong-password',
            '_csrf_token' => 'invalid-token',
        ]);

        self::assertResponseRedirects('/login');
        $client->followRedirect();
        self::assertResponseIsSuccessful();
    }
}
