<?php

declare(strict_types=1);

namespace App\UI\Http;

use App\Domain\Repository\GameRepositoryInterface;
use App\Domain\Repository\WordRepositoryInterface;
use App\Domain\UseCase\CreateGameUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/game', name: 'start_game', methods: ['GET'])]
class CreateGameAction
{
    private CreateGameActionResponder $actionResponder;
    private GameRepositoryInterface $gameRepository;
    private WordRepositoryInterface $wordRepository;
    private int $defaultMaxAttempts;
    private int $defaultDifficulty;

    public function __construct(
        CreateGameActionResponder $actionResponder,
        GameRepositoryInterface $gameRepository,
        WordRepositoryInterface $wordRepository,
        int $defaultMaxAttempts,
        int $defaultDifficulty
    ) {
        $this->actionResponder = $actionResponder;
        $this->gameRepository = $gameRepository;
        $this->wordRepository = $wordRepository;
        $this->defaultMaxAttempts = $defaultMaxAttempts;
        $this->defaultDifficulty = $defaultDifficulty;
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
    public function __invoke(Request $request): JsonResponse
    {
        $requestContent = json_decode($request->getContent(), true);
        $useCase = new CreateGameUseCase(
            gameRepository: $this->gameRepository,
            wordRepository: $this->wordRepository,
        );

        $maxAttempts = $requestContent['maxAttempts'] ?? $this->defaultMaxAttempts;
        $difficulty = $requestContent['difficulty'] ?? $this->defaultDifficulty;

        return $this->actionResponder->handle(
            $useCase->execute($maxAttempts, $difficulty)
        );
    }
}
