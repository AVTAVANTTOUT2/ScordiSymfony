<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\DirectMessage;
use App\Entity\DirectMessageThread;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dm')]
class DirectMessageController extends AbstractController
{
    #[Route('', name: 'app_dm_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $threads = $entityManager->getRepository(DirectMessageThread::class)->createQueryBuilder('t')
            ->andWhere('t.userA = :user OR t.userB = :user')
            ->setParameter('user', $user)
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();

        $search = trim((string) $request->query->get('q', ''));
        $users = [];
        if ($search !== '') {
            $users = $userRepository->createQueryBuilder('u')
                ->andWhere('u.username LIKE :query')
                ->andWhere('u.id != :current')
                ->setParameter('query', '%'.$search.'%')
                ->setParameter('current', $user->getId())
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        }

        return $this->render('direct_message/index.html.twig', [
            'threads' => $threads,
            'results' => $users,
            'query' => $search,
        ]);
    }

    #[Route('/start/{id}', name: 'app_dm_start', methods: ['POST'])]
    public function start(User $target, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $thread = $entityManager->getRepository(DirectMessageThread::class)->createQueryBuilder('t')
            ->andWhere('(t.userA = :u1 AND t.userB = :u2) OR (t.userA = :u2 AND t.userB = :u1)')
            ->setParameter('u1', $user)
            ->setParameter('u2', $target)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$thread instanceof DirectMessageThread) {
            $thread = (new DirectMessageThread())
                ->setUserA($user)
                ->setUserB($target);
            $entityManager->persist($thread);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dm_thread', ['id' => $thread->getId()]);
    }

    #[Route('/thread/{id}', name: 'app_dm_thread', methods: ['GET', 'POST'])]
    public function thread(DirectMessageThread $thread, Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$thread->hasParticipant($user)) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod('POST')) {
            $content = trim((string) $request->request->get('content', ''));
            if ($content !== '') {
                $message = (new DirectMessage())
                    ->setThread($thread)
                    ->setAuthor($user)
                    ->setContent($content);
                $entityManager->persist($message);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_dm_thread', ['id' => $thread->getId()]);
        }

        $messages = $entityManager->getRepository(DirectMessage::class)->createQueryBuilder('m')
            ->andWhere('m.thread = :thread')
            ->setParameter('thread', $thread)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('direct_message/thread.html.twig', [
            'thread' => $thread,
            'messages' => $messages,
            'other' => $thread->getOtherParticipant($user),
        ]);
    }
}
