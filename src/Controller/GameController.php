<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Ticket;
use App\Form\GameFormType;
use App\Form\TicketPurchaseFormType;
use App\Repository\GameRepository;
use App\Repository\TicketRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class GameController extends AbstractController
{
    #[Route('/game/add', name: 'game_add')]
    #[IsGranted('ROLE_ADMIN')]
    public function addGame(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $game = new Game();
        $form = $this->createForm(GameFormType::class, $game);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $game->setCreatedAt(new \DateTimeImmutable());
            $game->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('game_list');
        }

        return $this->render('game/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'game_list')]
    public function listGames(EntityManagerInterface $entityManager): Response
    {
        $games = $entityManager->getRepository(Game::class)->findAll();
        $gamesWithStatus = [];

        foreach ($games as $game) {
            $isFull = $game->getSoldTicketsCount() >= $game->getStadiumCapacity();
            $gamesWithStatus[] = [
                'game' => $game,
                'isFull' => $isFull,
            ];
        }

        return $this->render('game/list.html.twig', [
            'gamesWithStatus' => $gamesWithStatus,
        ]);
    }

    #[Route('/game/edit/{id}', name: 'game_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editGame(int $id, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $game = $entityManager->getRepository(Game::class)->find($id);
        if (!$game) {
            throw $this->createNotFoundException($translator->trans('game_not_found'));
        }

        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $game->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();

            return $this->redirectToRoute('game_list');
        }

        return $this->render('game/edit.html.twig', [
            'form' => $form->createView(),
            'game' => $game,
        ]);
    }

    #[Route('/game/delete/{id}', name: 'game_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteGame(int $id, EntityManagerInterface $entityManager, TranslatorInterface $translator, TicketRepository $ticketRepository, CommentRepository $commentRepository): Response
    {
        $game = $entityManager->getRepository(Game::class)->find($id);
        if (!$game) {
            throw $this->createNotFoundException($translator->trans('game_not_found'));
        }
        $tickets = $ticketRepository->findBy(['game' => $game]);
        foreach ($tickets as $ticket) {
            $entityManager->remove($ticket);
        }
        $comments = $commentRepository->findBy(['game' => $game]);
        foreach ($comments as $comment) {
            $entityManager->remove($comment);
        }

        $entityManager->remove($game);
        $entityManager->flush();

        return $this->redirectToRoute('game_list');
    }

    #[Route('/buy-ticket/{id}', name: 'buy_ticket')]
    #[IsGranted('ROLE_USER')]
    public function buyTicket(int $id, Request $request, GameRepository $gameRepository, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $game = $gameRepository->find($id);
        if (!$game) {
            throw $this->createNotFoundException($translator->trans('game_not_found'));
        }

        $soldTicketsCount = $game->getSoldTicketsCount();
        $availableTickets = $game->getStadiumCapacity() - $soldTicketsCount;

        if ($availableTickets <= 0) {
            $this->addFlash('error', $translator->trans('no_tickets_available'));
            return $this->redirectToRoute('game_list');
        }

        $form = $this->createForm(TicketPurchaseFormType::class, null, [
            'max_tickets' => $availableTickets
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quantity = $form->get('quantity')->getData();
            $user = $this->getUser();

            if ($quantity > $availableTickets) {
                $this->addFlash('error', $translator->trans('not_enough_tickets_available'));
                return $this->redirectToRoute('buy_ticket', ['id' => $game->getId()]);
            }

            for ($i = 0; $i < $quantity; $i++) {
                $ticket = new Ticket();
                $ticket->setGame($game);
                $ticket->setBuyer($user);
                $ticket->setPurchaseDate(new \DateTime());
                $ticket->setStatus('purchased');

                $em->persist($ticket);
            }

            $em->flush();

            $this->addFlash('success', $translator->trans('ticket_purchased'));
            return $this->redirectToRoute('game_list');
        }

        return $this->render('game/buy_ticket.html.twig', [
            'form' => $form->createView(),
            'game' => $game,
        ]);
    }

    #[Route('/game/tickets/{id}', name: 'game_tickets')]
    #[IsGranted('ROLE_ADMIN')]
    public function viewTickets(int $id, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $game = $entityManager->getRepository(Game::class)->find($id);
        if (!$game) {
            throw $this->createNotFoundException($translator->trans('game_not_found'));
        }

        $tickets = $entityManager->getRepository(Ticket::class)->findBy(['game' => $game]);

        return $this->render('game/tickets.html.twig', [
            'game' => $game,
            'tickets' => $tickets,
        ]);
    }
}
