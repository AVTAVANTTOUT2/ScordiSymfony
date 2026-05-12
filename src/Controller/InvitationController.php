<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Server;
use App\Entity\User;
use App\Service\InvitationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvitationController extends AbstractController
{
    #[Route('/server/{id}/invite', name: 'app_invitation_create', methods: ['POST'])]
    public function create(Server $server, InvitationService $invitationService): Response
    {
        $invitation = $invitationService->create($server);
        $this->addFlash('success', sprintf('Invitation créée : %s', $invitation->getCode()));

        return $this->redirectToRoute('app_server_show', ['id' => $server->getId()]);
    }

    #[Route('/invite/{code}', name: 'app_invitation_join', methods: ['GET'])]
    public function join(string $code, InvitationService $invitationService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $server = $invitationService->joinByCode($code, $user);

        if (!$server instanceof Server) {
            throw $this->createNotFoundException('Invitation invalide.');
        }

        return $this->redirectToRoute('app_server_show', ['id' => $server->getId()]);
    }
}
