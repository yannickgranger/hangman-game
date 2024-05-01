<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Domain\HangmanGame;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class GameContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    private HangmanGame $game;

    /**
     * @BeforeScenario
     */
    public function createGame()
    {
        $this->game = new HangmanGame('apple', 6);
    }

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given the secret word is :arg1
     */
    public function theSecretWordIs($arg1)
    {
        assertEquals($arg1, $this->game->getWord()->getValue());
    }
    /**
     * @Given the player has :arg1 attempts
     */
    public function thePlayerHasAttempts($arg1)
    {
        assertEquals($arg1, $this->game->getRemainingAttempts());
    }

    /**
     * @When the player guesses the letter :arg1
     */
    public function thePlayerGuessesTheLetter($arg1)
    {
        $this->game->playTurn($arg1);
    }

    /**
     * @Then the revealed word should be :arg1
     */
    public function theRevealedWordShouldBe($arg1)
    {
        assertEquals($arg1, $this->game->getWord()->getDisplay());
    }

    /**
     * @Then the player should have :arg1 remaining attempts
     */
    public function thePlayerShouldHaveRemainingAttempts($arg1)
    {
        assertEquals($arg1, $this->game->getRemainingAttempts());
    }

    /**
     * @When the player guesses the letters:
     */
    public function thePlayerGuessesTheLetters(TableNode $table)
    {
        $table = $table->getTable();
        $letters = array_pop($table);
        foreach ($letters as $letter) {
            $this->game->playTurn($letter);
        }
    }
    /**
     * @Then the game should be over
     */
    public function theGameShouldBeOver(): void
    {
        assertTrue($this->game->isGameOver());
    }
}
