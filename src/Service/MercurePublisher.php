<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MercurePublisher
{
    public function __construct(private readonly HubInterface $hub)
    {
    }

    /**
     * @param list<string> $topics
     * @param array<string, mixed> $payload
     */
    public function publish(array $topics, array $payload): void
    {
        $update = new Update($topics, json_encode($payload, JSON_THROW_ON_ERROR));
        $this->hub->publish($update);
    }
}
