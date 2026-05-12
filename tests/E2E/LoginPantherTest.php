<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

class LoginPantherTest extends PantherTestCase
{
    public function testLoginPageCanRenderInRealBrowser(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/login');

        self::assertStringContainsString('Connexion', $crawler->filter('h1')->text());
    }
}
