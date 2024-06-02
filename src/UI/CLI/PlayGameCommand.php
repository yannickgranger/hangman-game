<?php

declare(strict_types=1);

namespace App\UI\CLI;

use App\Domain\UseCase\PlayGameUseCase;
use App\Domain\ValueObject\Letter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:hangman:play', description: 'Start the hangman game')]
class PlayGameCommand extends Command
{
    private PlayGameUseCase $playGameUseCase;

    public function __construct(PlayGameUseCase $playGameUseCase, ?string $name = 'app:hangman:start')
    {
        parent::__construct($name);
        $this->playGameUseCase = $playGameUseCase;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $game = $this->playGameUseCase->startGame();
        $output->writeln(sprintf("Welcome to Hangman! The word has %s letters.\n", strlen($game->getWord()->getValue())));

        while (!$game->isGameOver()) {
            $output->writeln($game->getWord()->getDisplay());
            $guess = $this->askForGuess($input, $output);
            $isCorrect = $this->playGameUseCase->makeGuess($game, $guess);
            $output->writeln($game->getFeedback(letter: $guess, isCorrect: $isCorrect));
        }

        $output->writeln($game->getResultMessage());

        return Command::SUCCESS;
    }

    private function askForGuess(InputInterface $input, OutputInterface $output): Letter
    {
        $helper = $this->getHelper('question');
        $question = new Question('Guess a letter: ');
        $answer = $helper->ask($input, $output, $question);

        return new Letter(strtolower($answer));
    }
}
