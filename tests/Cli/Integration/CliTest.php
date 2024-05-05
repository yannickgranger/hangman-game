<?php

declare(strict_types=1);

namespace App\Tests\Cli\Integration;

use App\Domain\Entity\HangmanGame;
use App\Domain\ValueObject\Letter;
use PHPUnit\Framework\TestCase;

class CliTest extends TestCase
{
    public function testNewGameInitialization()
    {
        $game = new HangmanGame();

        $this->assertNotEmpty($game->getWord());
        $this->assertGreaterThan(0, $game->getRemainingAttempts());
        $this->assertEquals(str_repeat('-', strlen($game->getWord()->getValue())), $game->getWord()->getDisplay());
    }

    public function testGuessingCorrectLetter()
    {
        $game = new HangmanGame('secret');
        $game->guess(new Letter('s'));

        $this->assertEquals(2, $game->getRemainingAttempts()); // Assuming initial attempts is 3
        $this->assertEquals('s-cret', $game->getWord()->getDisplay());
    }

    public function testGuessingIncorrectLetter()
    {
        $game = new HangmanGame('hello');
        $game->guess(new Letter('z'));

        $this->assertEquals(2, $game->getRemainingAttempts()); // Assuming initial attempts is 3
        $this->assertEquals('-----o', $game->getWord()->getDisplay());
    }

    public function testGuessingSameLetter()
    {
        $game = new HangmanGame('hello');
        $game->guess(new Letter('o'));
        $game->guess(new Letter('o')); // Guessing the same letter twice

        $this->assertEquals(2, $game->getRemainingAttempts()); // Attempts shouldn't change
        $this->assertEquals('-----o', $game->getWord()->getDisplay()); // Word representation shouldn't change
    }

    public function testRequestingSingleLetterHint()
    {
        $game = new HangmanGame('magic');
        $game->setWordSelectionMock(function () {
            return 'magic'; // Mock word selection for testing
        });

        // Mock logic to reveal a letter (e.g., 'm')

        $game->requestHint();

        $this->assertEquals(2, $game->getRemainingAttempts()); // Assuming hint reduces attempts
        $this->assertEquals('m---ic', $game->getWord()->getDisplay());
    }
    public function testStartingMultiplayerGame()
    {
        $game = new HangmanGame(true); // Enable multiplayer mode

        $this->assertCount(2, $game->getPlayers()); // Assuming default players are 2
    }
    public function testMultiPlayerTakingTurns()
    {
        $game = new HangmanGame(true);
        $player1 = $game->getCurrentPlayer(); // Assuming initial player is 1

        $game->guess(new Letter('h')); // Simulate guess by player 1

        $this->assertNotSame($player1, $game->getCurrentPlayer()); // Verify turn switched to player 2

        $game->guess(new Letter('e')); // Simulate guess by player 2

        $this->assertSame($player1, $game->getCurrentPlayer()); // Verify turn back to player 1
    }

    public function testSkippingPlayerWithNoAttempts()
    {
        $prophet = new Prophet();
        $player1 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');
        $player2 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');

        $game = new HangmanGame(true); // Enable multiplayer mode
        $game->setPlayers([$player1->reveal(), $player2->reveal()]); // Mock players

        $player1->getRemainingAttempts()->willReturn(0); // Mock player 1 with no attempts

        $game->guess(new Letter('x')); // Simulate guess (doesn't matter who guesses)

        $prophet->checkPredictions();
        $this->assertSame($player2->reveal(), $game->getCurrentPlayer()); // Verify turn switched to player 2
    }

    public function testWinningInMultiplayer()
    {
        $prophet = new Prophet();
        $player1 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');
        $player2 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');

        $game = new HangmanGame(true); // Enable multiplayer mode
        $game->setPlayers([$player1->reveal(), $player2->reveal()]); // Mock players

        // Simulate player 1 guessing all letters correctly
        $game->guess(new Letter('h'));
        $game->guess(new Letter('e'));
        $game->guess(new Letter('l'));
        $game->guess(new Letter('o')); // Winning guess for player 1

        $this->assertTrue($game->isGameOver()); // Verify game is over
        $this->assertSame($player1->reveal(), $game->getWinningPlayer()); // Verify winning player

        $prophet->checkPredictions();
    }

    public function testLosingInMultiplayer()
    {
        $prophet = new Prophet();
        $player1 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');
        $player2 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');

        $game = new HangmanGame(true); // Enable multiplayer mode
        $game->setPlayers([$player1->reveal(), $player2->reveal()]); // Mock players

        // Simulate player 1 and player 2 using all attempts with incorrect guesses
        $game->guess(new Letter('x'), $player1->reveal());
        $game->guess(new Letter('y'), $player1->reveal());
        $game->guess(new Letter('z'), $player1->reveal());
        $game->guess(new Letter('p'), $player2->reveal());
        $game->guess(new Letter('q'), $player2->reveal());

        $this->assertTrue($game->isGameOver()); // Verify game is over
        $this->assertEquals(0, $player1->getRemainingAttempts()); // Verify player 1 attempts are zero
        $this->assertEquals(0, $player2->getRemainingAttempts()); // Verify player 2 attempts are zero

        $prophet->checkPredictions();
    }
    public function testWinningTheGame()
    {
        $game = new HangmanGame('hello');
        $game->guess(new Letter('h'));
        $game->guess(new Letter('e'));
        $game->guess(new Letter('l'));
        $game->guess(new Letter('o')); // Winning guess

        $this->assertEquals(0, $game->getRemainingAttempts()); // All attempts used
        // Additional assertion to verify winning state (e.g., win message)
    }

    public function testLosingTheGame()
    {
        $game = new HangmanGame('world');
        $game->guess(new Letter('x'));
        $game->guess(new Letter('y'));
        $game->guess(new Letter('z'));
        $game->guess(new Letter('p')); // Losing guess

        $this->assertEquals(0, $game->getRemainingAttempts()); // All attempts used
        // Additional assertion to verify losing state (e.g., loss message)
    }
    public function testLosingTurnInMultiplayer()
    {
        $game = new HangmanGame(true);
        $player1 = $game->getPlayer(1); // Assuming players are indexed

        $game->guess(new Letter('x'), $player1); // Simulate player 1 guess

        $player2 = $game->getCurrentPlayer();
        $this->assertNotSame($player1, $player2); // Verify turn switched to player 2
        $this->assertEquals(2, $player1->getRemainingAttempts()); // Assuming initial attempts is 3
    }
}