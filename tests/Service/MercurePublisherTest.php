<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\MercurePublisher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mercure\HubInterface;

class MercurePublisherTest extends TestCase
{
    public function testPublishCallsHub(): void
    {
        $hub = $this->createMock(HubInterface::class);
        $hub->expects(self::once())->method('publish');

        $publisher = new MercurePublisher($hub);
        $publisher->publish(['channel/1'], ['type' => 'ping']);
    }
}
