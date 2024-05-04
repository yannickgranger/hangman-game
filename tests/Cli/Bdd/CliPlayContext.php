<?php

declare(strict_types=1);

namespace App\Tests\Cli\Bdd;

use App\Domain\Entity\HangmanGame;
use App\Domain\UseCase\PlayGameUseCase;
use App\Domain\ValueObject\Letter;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertTrue;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class CliPlayContext implements Context
{
    private PlayGameUseCase $playGameUseCase;
    private HangmanGame $game;
    private string $displayedOutput;
    private KernelInterface $kernel;

    public function __construct(PlayGameUseCase $playGameUseCase, KernelInterface $kernel)
    {
        $this->playGameUseCase = $playGameUseCase;
        $this->kernel = $kernel;
    }

    /**
     * @Given I start a new game
     */
    public function iStartANewGame()
    {
        $this->game = $this->playGameUseCase->startGame();
    }

    /**
     * @Then a random word should be chosen
     */
    public function aRandomWordShouldBeChosen()
    {
        assertIsString($this->game->getWord()->getValue());
        assertGreaterThan(0, strlen($this->game->getWord()->getValue()));
    }

    /**
     * @Then the number of remaining attempts should be displayed
     */
    public function theNumberOfRemainingAttemptsShouldBeDisplayed()
    {
        preg_match('#You have \\d guesses left#', $this->game->getResultMessage(), $matches);
        assertCount(1, $matches);
    }

    /**
     * @Then the word should be displayed with underscore representing hidden letters
     */
    public function theWordShouldBeDisplayedWithDashesRepresentingHiddenLetters()
    {
        assertStringContainsString(
            "______",
            $this->game->getResultMessage()
        );
    }

    /**
     * @Given a new game has started
     */
    public function aNewGameHasStarted()
    {
        $this->game = $this->playGameUseCase->startGame();
    }

    /**
     * @When I guess a letter that exists in the word
     */
    public function iGuessALetterThatExistsInTheWord()
    {
        assertTrue($this->playGameUseCase->makeGuess(
            $this->game,
            new Letter("a")
        ));
    }
    /**
     * @When I guess a letter that does not exist in the word
     */
    public function iGuessALetterThatDoesNotExistInTheWord()
    {
        assertFalse($this->playGameUseCase->makeGuess(
            $this->game,
            new Letter("z")
        ));
    }


    /**
     * @Then the letter should be revealed in all its correct positions
     */
    public function theLetterShouldBeRevealedInAllItsCorrectPositions()
    {
        assertStringContainsString("You guessed the letter a.",
            $this->game->getFeedback(new Letter("a"), true)
        );
        assertEquals("a_a_a_",$this->game->getWord()->getDisplay());
    }


    /**
     * @Then the number of remaining attempts should not change
     */
    public function theNumberOfRemainingAttemptsShouldNotChange()
    {
        $maxAttempts = $this->kernel->getContainer()->getParameter('max_attempts');
        assertEquals(
            $this->game->getRemainingAttempts(), $maxAttempts
        );
    }
    /**
     * @Then one attempt should be deducted from the remaining attempts
     */
    public function oneAttemptShouldBeDeductedFromTheRemainingAttempts()
    {
        $maxAttempts = $this->kernel->getContainer()->getParameter('max_attempts');
        assertEquals(
            $this->game->getRemainingAttempts(), $maxAttempts - 1
        );
    }

    /**
     * @Then the word should remain hidden with underscore
     */
    public function theWordShouldRemainHiddenWithUnderscore()
    {
        assertEquals(
            $this->game->getWord()->getDisplay(),
            str_repeat('_', strlen($this->game->getWord()->getValue()))
        );
    }


    /**
     * @When I guess all the letters in the word correctly
     */
    public function iGuessAllTheLettersInTheWordCorrectly()
    {
        $this->game->playTurn(new Letter('a'));
        $this->game->playTurn(new Letter('n'));
        $this->game->playTurn(new Letter('s'));
        assertTrue($this->game->getWord()->isRevealed());
    }

    /**
     * @Then a message indicating victory should be displayed
     */
    public function aMessageIndicatingVictoryShouldBeDisplayed()
    {
        assertEquals(
            'Congratulations! You guessed the word: ananas',
            $this->game->getResultMessage()
        );
    }

    /**
     * @Then the complete word should be shown
     */
    public function theCompleteWordShouldBeShown()
    {
        assertStringContainsString('ananas',
        $this->game->getResultMessage());
    }
    /**
     * @When I use up all my attempts by guessing incorrect letters
     */
    public function iUseUpAllMyAttemptsByGuessingIncorrectLetters()
    {
        $this->game->playTurn(new Letter('t'));
        $this->game->playTurn(new Letter('u'));
        $this->game->playTurn(new Letter('v'));
        $this->game->playTurn(new Letter('w'));
        $this->game->playTurn(new Letter('x'));
        $this->game->playTurn(new Letter('y'));
        assertEquals(0, $this->game->getRemainingAttempts());
    }

    /**
     * @Then a message indicating defeat should be displayed
     */
    public function aMessageIndicatingDefeatShouldBeDisplayed()
    {
        assertEquals($this->game->getResultMessage(),'You ran out of guesses. The word was: ananas');
    }
}
