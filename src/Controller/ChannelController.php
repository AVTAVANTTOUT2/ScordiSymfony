<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Server;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/server/{server}/channel')]
class ChannelController extends AbstractController
{
    #[Route('/{channel}', name: 'app_channel_show', methods: ['GET'])]
    public function show(Server $server, Channel $channel, MessageRepository $messageRepository): Response
    {
        if ($channel->getServer()?->getId() !== $server->getId()) {
            throw $this->createNotFoundException('Salon invalide pour ce serveur.');
        }
        $this->denyAccessUnlessGranted('CHANNEL_VIEW', $channel);

        return $this->render('channel/show.html.twig', [
            'server' => $server,
            'channel' => $channel,
            'messages' => array_reverse($messageRepository->findRecentInChannel($channel, 30)),
        ]);
    }
}
