<?php

declare(strict_types=1);

namespace App\UI\Http;

use App\Domain\Entity\HangmanGame;
use App\Domain\Exception\GameNotFoundException;
use App\Domain\Exception\InvalidGuessException;
use App\Domain\Repository\GameRepositoryInterface;
use App\Domain\UseCase\GuessLetterUseCase;
use App\UI\Http\Validation\LetterValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: "/api/game/id/guess", name: "game_guess_letter", methods: ["GET"])]
final class GuessLetterAction
{
    private GuessLetterResponder $guessLetterResponder;
    private GameRepositoryInterface $gameRepository;
    private LetterValidator $guessLetterInputValidator;

    public function __construct(
        GuessLetterResponder $guessLetterResponder,
        LetterValidator $guessLetterInputValidator,
        GameRepositoryInterface $gameRepository,
    ) {
        $this->guessLetterResponder = $guessLetterResponder;
        $this->guessLetterInputValidator = $guessLetterInputValidator;
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(Request $request, string $id, string $letter): JsonResponse
    {
        $requestContent = json_decode($request->getContent(), true);

        // here you should validate request with Validator (Sf validator or custom)
        // for example create a validator service and use berberlei/asserts or webmozart/asserts
        // SF paramConverter magic already eliminates some base cases but you still need to validate edge cases
        $game = $this->gameRepository->find($id);

        // can pass it the full request or just the parameters, depending on complexity
        $this->guessLetterInputValidator->validateLetter($letter);

        // sidenote:
        // when you throw exceptions,
        // remember to can create an exception listener for exceptions you forgot to catch
        // because you are supposed to catch exceptions ...
        if(!$game instanceof HangmanGame) {
            throw new GameNotFoundException();
        }

        $useCase = new GuessLetterUseCase($game, $letter);

        // for business rules violations, in the $useCase->execute($game, $letter) there's choices in handling
        // you could create an error object, return it and handle it in the responder.
        // Or, for better concerns separation and readability, just throw in use case and catch in action.
        try {
            $game = $useCase->execute($game, $letter);
        } catch (GameNotFoundException $e) {
            // Handle GameNotFoundException in responder
            return $this->guessLetterResponder->handleException($e);
        } catch (InvalidGuessException $e) {
            // Handle InvalidGuessException in responder
            return $this->guessLetterResponder->handleException($e);
        }

        return $this->guessLetterResponder->handle($game);
    }
}
