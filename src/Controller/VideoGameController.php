<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\VideoGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VideoGameController extends AbstractController
{
    public function __construct(
        private readonly VideoGameRepository $videoGameRepository,
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
}
