<?php

declare(strict_types=1);

namespace App\Tests\Hexagon\Bdd;

use App\Domain\Entity\HangmanGame;
use App\Domain\ValueObject\Letter;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
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
    private HangmanGame $game;

    /**
     * @BeforeScenario
     */
    public function createGame(): void
    {
        $this->game = new HangmanGame('apple', 6, 5);
    }

    /**
     * @Given the secret word is :arg1
     */
    public function theSecretWordIs(string $arg1): void
    {
        assertEquals($arg1, $this->game->getWord()->getValue());
    }
    /**
     * @Given the player has :arg1 attempts
     */
    public function thePlayerHasAttempts(string $arg1): void
    {
        assertEquals($arg1, $this->game->getRemainingAttempts());
    }

    /**
     * @When the player guesses the letter :arg1
     */
    public function thePlayerGuessesTheLetter(string $arg1): void
    {
        $this->game->playTurn(new Letter($arg1));
    }

    /**
     * @Then the revealed word should be :arg1
     */
    public function theRevealedWordShouldBe(string $arg1): void
    {
        assertEquals($arg1, $this->game->getWord()->getDisplay());
    }

    /**
     * @Then the player should have :arg1 remaining attempts
     */
    public function thePlayerShouldHaveRemainingAttempts(string $arg1): void
    {
        assertEquals($arg1, $this->game->getRemainingAttempts());
    }

    /**
     * @When the player guesses the letters:
     */
    public function thePlayerGuessesTheLetters(TableNode $table): void
    {
        $table = $table->getTable();
        $letters = array_pop($table);
        foreach ($letters as $letter) {
            $this->game->playTurn(new Letter($letter));
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
