<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\VideoGame;
use App\Form\VideoGameType;
use App\Repository\VideoGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VideoGameController extends AbstractController
{
    public function __construct(
        private readonly VideoGameRepository $videoGameRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/video-game', name: 'app_video_game_index')]
    public function index(): Response
    {
        return $this->render('video_game/index.html.twig', [
            'games' => $this->videoGameRepository->findAll(),
        ]);
    }

    #[Route('/video-game/{id}', name: 'app_video_game_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): Response
    {
        $videoGame = $this->videoGameRepository->find($id);
        // De manière explicite on pourrait également utiliser
        // $this->videoGameRepository->findOneBy(['id' => $id]);

        if ($videoGame === null) {
            throw $this->createNotFoundException('Video game not found');
        }

        return $this->render('video_game/show.html.twig', [
            'game' => $videoGame,
        ]);
    }

    #[Route('/video-game/{id}/edit', name: 'app_video_game_edit', requirements: ['id' => '\d+'], methods: ['GET', 'PUT'])]
    public function edit(int $id, Request $request): Response
    {
        $videoGame = $this->videoGameRepository->find($id);
        if ($videoGame === null) {
            // Déclenchement d'une erreur 404 si aucun jeu vidéo à cet ID
            throw $this->createNotFoundException('Video game not found');
        }


        $form = $this->createForm(VideoGameType::class, $videoGame, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $videoGame = $form->getData();
            // On peut directement faire un "flush" car le Jeu vidéo est en cours de traitement par l'EntityManager
            // L'objet est modifié par le formulaire. Il s'agit de la même instance de l'objet VidéoGame
            $this->entityManager->flush();

            $this->addFlash('success', 'Video game updated.');
            return $this->redirectToRoute('app_video_game_show', ['id' => $videoGame->getId()]);
        }

        return $this->render('video_game/edit.html.twig', [
            'game' => $videoGame,
            'form' => $form->createView(),
        ]);
    }
}
