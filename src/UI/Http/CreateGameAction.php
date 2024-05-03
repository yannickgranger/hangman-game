<?php

declare(strict_types=1);

namespace App\UI\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: "/api/game", name: "start_game", methods: ["POST"])]

final class CreateGameAction
{
    private CreateGameUseCaseInterface $createGameUseCase;
    private CreateGameActionResponder $actionResponder;

    public function __construct(
        CreateGameUseCaseInterface $createGameUseCase,
        CreateGameActionResponder $actionResponder
    ) {
        $this->createGameUseCase = $createGameUseCase;
        $this->actionResponder = $actionResponder;
    }

    /*
     * To create the game, you can also use a CreateGameUseCase, A GameFactory, or a GameService
     * Each one of them has pros / cons
     * For the most basic game, prefer a CreateGameUseCase (clear separations of concerns, re-usable for multiple UI, DDD approach)
     * If you want to create more complex configurations of game, prefer a factory (loose coupling, multiple configs, but little over-engineered)
     * Or you can use the GameServiceInterface to centralize the interactions with the game (easy to maintain, easy to mock, but becomes a big class)
     * Note that the CreateGameUseCase can be considered as a Command if you decide to use CQ(R)S + Events architecture
     * The App directory is usually the place for Command/CommandHandler Query/QueryHandler or here CRUD UseCases, or e.g. things that does not
     * fit exactly in domain or infrastructure, but in domain, near to infrastructure concerns
     * App is in theory a part of the domain we choose to put in a dedicated directory to improve readability of code
     * */
    public function __invoke(Request $request, int $maxAttempts, ?int $difficulty): JsonResponse
    {
        $game = $this->createGameUseCase->execute($maxAttempts, $difficulty);
        return $this->actionResponder->handle($game);
    }
}
