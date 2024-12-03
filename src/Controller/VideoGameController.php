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
            throw new $this->createNotFoundException('Video game not found');
        }

        return $this->render('video_game/show.html.twig', [
            'game' => $videoGame,
        ]);
    }

    /**
     * Ici, contrairement à la méthode "show" l'objet VideoGame est mappé directement depuis la requête
     *
     */
    #[Route('/video-game/{id}/edit', name: 'app_video_game_edit', requirements: ['id' => '\d+'], methods: ['GET', 'PUT', 'POST'])]
    public function edit(VideoGame $videoGame, Request $request): Response
    {
        $form = $this->createForm(VideoGameType::class, $videoGame);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO
        }

        return $this->render('video_game/edit.html.twig', [
            'game' => $videoGame,
            'form' => $form->createView(),
        ]);
    }
}
