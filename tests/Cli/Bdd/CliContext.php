<?php

declare(strict_types=1);

namespace App\Tests\Cli\Bdd;

use App\Domain\Entity\HangmanGame;
use App\Domain\Repository\WordRepositoryInterface;
use App\Domain\ValueObject\Letter;
use App\UI\CLI\SubCommand\GuessLetterCommand;
use App\UI\CLI\SubCommand\InitializeGameCommand;
use Behat\Behat\Context\Context;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use function PHPUnit\Framework\assertInstanceOf;

class CliContext implements Context
{
    private Command $initializeCommand;
    private string $output;
    private HangmanGame $game;

    public function __construct(WordRepositoryInterface $wordRepository)
    {
        $this->initializeCommand = new InitializeGameCommand(
            $wordRepository
        );
    }

    /**
     * @Given i launch the command :arg1
     */
    public function iLaunchTheCommand(string $arg1)
    {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $this->initializeCommand->run($input, $output);
        $this->output = $output->fetch();
    }

    /**
     * @Then the output should be :arg1
     */
    public function theOutputShouldBe($arg1)
    {
        if (preg_match("#$arg1#", $this->output, $matches)) {
            $number = $matches[0];
            echo "The output contains the expression: $number";
        } else {
            throw new \RuntimeException(
                sprintf("The output %s does not contain the searched expression.", $this->output)
            );
        }
    }

    /**
     * @Given I have initialized a game
     */
    public function iHaveInitializedAGame()
    {
        assertInstanceOf(InitializeGameCommand::class, $this->initializeCommand);
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $this->initializeCommand->run($input, $output);
        $this->output = $output->fetch();
        $game = $this->initializeCommand->getGame();
        assertInstanceOf(HangmanGame::class, $game);
        $this->game = $game;
    }

    /**
     * @Given I guess the letter :arg1 using the :arg2
     */
    public function iGuessTheLetterUsingThe($arg1, $arg2)
    {
        $this->guessLetterCommand = new GuessLetterCommand(
            game: $this->game,
            letter: new Letter($arg1)
        );
    }
}
