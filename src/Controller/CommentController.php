<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Game;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class CommentController extends AbstractController
{
    #[Route('/comment/new/{gameId}', name: 'comment_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(int $gameId, Request $request, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $game = $em->getRepository(Game::class)->find($gameId);
        if (!$game) {
            throw $this->createNotFoundException($translator->trans('game_not_found'));
        }

        $comment = new Comment();
        $comment->setGame($game);
        $comment->setAuthor($this->getUser());
        $comment->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('comment_show', ['gameId' => $gameId]);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
            'game' => $game,
        ]);
    }

    #[Route('/comment/show/{gameId}', name: 'comment_show', methods: ['GET'])]
    public function show(int $gameId, CommentRepository $commentRepository, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $comments = $commentRepository->findBy(['game' => $gameId]);
        $game = null;

        if (count($comments) > 0) {
            $game = $comments[0]->getGame();
        } else {
            $game = $em->getRepository(Game::class)->find($gameId);
            if (!$game) {
                throw $this->createNotFoundException($translator->trans('game_not_found'));
            }
        }

        return $this->render('comment/show.html.twig', [
            'comments' => $comments,
            'game' => $game,
        ]);
    }
}

// namespace App\Controller;

// use App\Entity\Comment;
// use App\Entity\Game;
// use App\Form\CommentType;
// use App\Repository\CommentRepository;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
// use Symfony\Contracts\Translation\TranslatorInterface;

// class CommentController extends AbstractController
// {
//     #[Route('/comment/new/{gameId}', name: 'comment_new', methods: ['GET', 'POST'])]
//     #[IsGranted('ROLE_USER')]
//     public function new(int $gameId, Request $request, EntityManagerInterface $em, TranslatorInterface $translator): Response
//     {
//         $game = $em->getRepository(Game::class)->find($gameId);
//         if (!$game) {
//             throw $this->createNotFoundException($translator->trans('game_not_found'));
//         }

//         $comment = new Comment();
//         $comment->setGame($game);
//         $comment->setAuthor($this->getUser());
//         $comment->setCreatedAt(new \DateTimeImmutable());

//         $form = $this->createForm(CommentType::class, $comment);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $em->persist($comment);
//             $em->flush();

//             return $this->redirectToRoute('comment_show', ['gameId' => $gameId]);
//         }

//         return $this->render('comment/new.html.twig', [
//             'comment' => $comment,
//             'form' => $form->createView(),
//             'game' => $game,
//         ]);
//     }

//     #[Route('/comment/show/{gameId}', name: 'comment_show', methods: ['GET'])]
//     public function show(int $gameId, CommentRepository $commentRepository): Response
//     {
//         $comments = $commentRepository->findBy(['game' => $gameId]);
//         $game = $comments[0]->getGame(); // Assuming there is at least one comment

//         return $this->render('comment/show.html.twig', [
//             'comments' => $comments,
//             'game' => $game,
//         ]);
//     }
// }
