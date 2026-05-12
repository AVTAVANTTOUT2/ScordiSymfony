<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Message;
use App\Entity\Server;
use App\Entity\User;
use App\Service\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    #[Route('/server/{server}/channel/{channel}/message', name: 'app_message_send', methods: ['POST'])]
    public function send(Server $server, Channel $channel, Request $request, MessageService $messageService): Response
    {
        if ($channel->getServer()?->getId() !== $server->getId()) {
            throw $this->createNotFoundException('Salon introuvable.');
        }
        $this->denyAccessUnlessGranted('CHANNEL_VIEW', $channel);

        /** @var User $user */
        $user = $this->getUser();
        $content = trim((string) $request->request->get('content', ''));
        if ($content !== '') {
            $messageService->send($channel, $user, $content);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/message/{id}/edit', name: 'app_message_edit', methods: ['POST'])]
    public function edit(Message $message, Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($message->getAuthor()?->getId() !== $user->getId()) {
            return new Response('Forbidden', Response::HTTP_FORBIDDEN);
        }

        $message->setContent(trim((string) $request->request->get('content', '')));
        $message->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/message/{id}/delete', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Message $message, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('MESSAGE_DELETE_ANY', $message);
        $entityManager->remove($message);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
