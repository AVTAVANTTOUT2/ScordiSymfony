<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Server;
use App\Entity\User;
use App\Repository\ChannelRepository;
use App\Form\ServerType;
use App\Service\ServerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/server')]
class ServerController extends AbstractController
{
    #[Route('/new', name: 'app_server_new')]
    public function new(Request $request, ServerService $serverService): Response
    {
        $form = $this->createForm(ServerType::class, new Server());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $server = $serverService->create((string) $form->get('name')->getData(), $user);

            return $this->redirectToRoute('app_server_show', ['id' => $server->getId()]);
        }

        return $this->render('server/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}', name: 'app_server_show')]
    public function show(Server $server, ChannelRepository $channelRepository): Response
    {
        $firstTextChannel = $channelRepository->findOneBy(
            ['server' => $server, 'type' => 'text'],
            ['position' => 'ASC', 'id' => 'ASC']
        );

        if (null !== $firstTextChannel) {
            return $this->redirectToRoute('app_channel_show', [
                'server' => $server->getId(),
                'channel' => $firstTextChannel->getId(),
            ]);
        }

        return $this->render('server/show.html.twig', [
            'server' => $server,
        ]);
    }
}
