<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Channel;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use App\Service\MarkdownRenderer;
use App\Service\PresenceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class PollingController extends AbstractController
{
    #[Route('/channel/{id}/poll', name: 'app_api_channel_poll', methods: ['GET'])]
    public function poll(
        Channel $channel,
        Request $request,
        MessageRepository $messageRepository,
        MarkdownRenderer $markdownRenderer,
    ): JsonResponse {
        $this->denyAccessUnlessGranted('CHANNEL_VIEW', $channel);
        $since = (int) $request->query->get('since', 0);
        $messages = $messageRepository->findSince($channel, $since);

        return $this->json([
            'messages' => array_map(
                static fn (Message $message): array => [
                    'id' => $message->getId(),
                    'content' => $message->getContent(),
                    'contentHtml' => $markdownRenderer->render((string) $message->getContent()),
                    'createdAt' => $message->getCreatedAt()->format(DATE_ATOM),
                    'createdAtTs' => $message->getCreatedAt()->getTimestamp(),
                    'author' => [
                        'id' => $message->getAuthor()?->getId(),
                        'username' => $message->getAuthor()?->getUsername(),
                        'presence' => $message->getAuthor()?->getPresenceStatus(),
                    ],
                ],
                $messages
            ),
            'serverTime' => time(),
        ]);
    }

    #[Route('/channel/{id}/history', name: 'app_api_channel_history', methods: ['GET'])]
    public function history(
        Channel $channel,
        Request $request,
        MessageRepository $messageRepository,
        MarkdownRenderer $markdownRenderer,
    ): JsonResponse {
        $this->denyAccessUnlessGranted('CHANNEL_VIEW', $channel);
        $before = $request->query->get('before');
        $beforeId = is_numeric($before) ? (int) $before : null;
        $messages = $messageRepository->findOlderThan($channel, $beforeId, 30);

        return $this->json([
            'messages' => array_map(
                static fn (Message $message): array => [
                    'id' => $message->getId(),
                    'content' => $message->getContent(),
                    'contentHtml' => $markdownRenderer->render((string) $message->getContent()),
                    'createdAt' => $message->getCreatedAt()->format(DATE_ATOM),
                    'createdAtTs' => $message->getCreatedAt()->getTimestamp(),
                    'author' => [
                        'id' => $message->getAuthor()?->getId(),
                        'username' => $message->getAuthor()?->getUsername(),
                        'presence' => $message->getAuthor()?->getPresenceStatus(),
                    ],
                ],
                array_reverse($messages)
            ),
        ]);
    }

    #[Route('/presence/heartbeat', name: 'app_api_presence_heartbeat', methods: ['POST'])]
    public function heartbeat(Request $request, PresenceService $presenceService): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $payload = json_decode((string) $request->getContent(), true);
        $status = is_array($payload) ? (string) ($payload['status'] ?? 'online') : 'online';

        $presenceService->heartbeat($user, $status);

        return $this->json([
            'ok' => true,
            'status' => $user->getPresenceStatus(),
            'lastSeenAt' => $user->getLastSeenAt()?->format(DATE_ATOM),
        ]);
    }
}
