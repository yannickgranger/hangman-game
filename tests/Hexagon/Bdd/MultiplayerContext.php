<?php

declare(strict_types=1);

namespace App\Tests\Hexagon\Bdd;

use App\Domain\Entity\HangmanGame;
use App\Domain\Entity\Player;
use App\Domain\Entity\Word;
use App\Domain\Repository\PlayerRepositoryInterface;
use App\Domain\ValueObject\Letter;
use Behat\Behat\Context\Context;
use Symfony\Component\Uid\Uuid;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertTrue;

class MultiplayerContext implements Context
{
    private PlayerRepositoryInterface $playerRepository;

    private ?HangmanGame $game = null;

    private ?string $joinMsg = null;

    private ?bool $result = null;

    private ?Letter $letter = null;

    public function __construct(PlayerRepositoryInterface $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    /**
     * @BeforeScenario
     */
    public function loadFixtures()
    {
        $this->playerRepository->flushAll();

        $id = Uuid::v4();
        $player = new Player($id, 'Alice');
        $this->playerRepository->save($id, $player);

        $id = Uuid::v4();
        $player = new Player($id, 'Bob');
        $this->playerRepository->save($id, $player);

        $id = Uuid::v4();
        $player = new Player($id, 'Charlie');
        $this->playerRepository->save($id, $player);
    }

    /**
     * @Given :arg3 players exist named :arg1, :arg2,
     */
    public function playersExistNamed($arg1, $arg2, $arg3)
    {
        for ($p = 1; $p < (int) $arg3; ++$p) {
            assertInstanceOf(Player::class,
                $this->playerRepository->getUserByUsername(${sprintf('arg%s', $p)}));
        }
    }

    /**
     * @When the first player :arg1 joins the game
     */
    public function theFirstPlayerJoinsTheGame($arg1)
    {
        $player = $this->playerRepository->getUserByUsername($arg1);
        assertInstanceOf(Player::class, $player);
        $this->joinMsg = $this->game->join($player);
    }

    /**
     * @Given a player named :arg1 exists
     */
    public function aPlayerNamedExists($arg1)
    {
        assertTrue($this->playerRepository->getUserByUsername($arg1) instanceof Player);
    }

    /**
     * @Then :arg1 should see a welcome message with the word and remaining guesses
     */
    public function shouldSeeAWelcomeMessageWithTheWordAndRemainingGuesses($arg1)
    {
        assertStringContainsString(sprintf('Player %s has joined the game!', $arg1), $this->joinMsg);
    }

    /**
     * @Then the shared screen should display the word with underscores
     */
    public function theSharedScreenShouldDisplayTheWordWithUnderscores()
    {
        assertStringContainsString('Welcome to Hangman! The word has 6 letters.', $this->joinMsg);
        assertStringContainsString('______', $this->joinMsg);
    }

    /**
     * @Then players should see a message indicating a good guess and that :arg1 is in the word
     */
    public function shouldSeeAMessageIndicatingAGoodGuessAndThatIsInTheWord($arg1)
    {
        if ($this->result) {
            assertStringContainsString(
                needle: 'Good guess!',
                haystack: $this->game->getFeedback($this->letter, true)
            );
        }
        if ($this->result) {
            assertStringContainsString(
                needle: sprintf('The letter %s is in the word.', $this->letter->getValue()),
                haystack: $this->game->getFeedback($this->letter, true)
            );
        }
    }

    /**
     * @Then the shared screen should update the word to reflect the guessed letter
     */
    public function theSharedScreenShouldUpdateTheWordToReflectTheGuessedLetter()
    {
        if ($this->result) {
            assertStringContainsString(
                needle: sprintf('The letter %s is in the word.', $this->letter->getValue()),
                haystack: $this->game->getFeedback($this->letter, true)
            );
        }
    }

    /**
     * @Then :arg1 should see a message welcoming them and informing them :arg2 has joined
     */
    public function shouldSeeAMessageWelcomingThemAndInformingThemHasJoined($arg1, $arg2)
    {
        assertStringContainsString(sprintf('Player %s has joined the game!', $arg2), $this->joinMsg);
    }

    /**
     * @Given a game exists with the word :arg1
     */
    public function aGameExistsWithTheWord($arg1)
    {
        $this->game = new HangmanGame(
            Uuid::v4(),
            new Word('secret'),
            6,
            5,
            true
        );
    }

    /**
     * @When :arg1 joins the game
     */
    public function joinsTheGame($arg1)
    {
        $player = $this->playerRepository->getUserByUsername($arg1);
        $this->joinMsg = $this->game->join($player);
    }

    /**
     * @When :arg1 guesses the letter :arg2
     */
    public function guessesTheLetter($arg1, $arg2)
    {
        $player = $this->playerRepository->getUserByUsername($arg1);
        $letter = new Letter($arg2);
        $this->result = $this->game->playTurn(letter: $letter, player: $player);
        $this->letter = $letter;
    }

    /**
     * @Then players should see a message congratulating them on winning and revealing the word :arg1
     */
    public function playersShouldSeeAMessageCongratulatingThemOnWinningAndRevealingTheWord($arg1)
    {
        if ($this->result) {
            assertTrue($this->game->isGameOver());
            assertStringContainsString(
                needle: sprintf('Congratulations! You guessed the word: %s', $this->game->getWord()->getValue()),
                haystack: $this->game->getResultMessage()
            );
        }
    }

    /**
     * @When :arg1 tries to guess a letter
     */
    public function triesToGuessALetter($arg1)
    {
        $player = $this->playerRepository->getUserByUsername($arg1);
        $letter = new Letter('z');
        $this->result = $this->game->playTurn(letter: $letter, player: $player);
    }

    /**
     * @Then :arg1 should be informed that the game is over
     */
    public function shouldBeInformedThatTheGameIsOver($arg1)
    {
        assertEquals('Game is over', $this->game->getFeedback($this->letter, $this->result));
    }
}
