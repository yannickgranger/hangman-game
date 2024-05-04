<?php

declare(strict_types=1);

namespace App\Tests\Cli\Integration;

use App\Domain\Entity\HangmanGame;
use App\Domain\ValueObject\Letter;
use PHPUnit\Framework\TestCase;

class CliPlayTest extends TestCase
{
    public function testNewGameInitialization()
    {
        $game = new HangmanGame('test', 6);
        $this->assertNotEmpty($game->getWord());
        $this->assertGreaterThan(0, $game->getRemainingAttempts());
        $this->assertEquals(
            str_repeat(
                '_',
                strlen($game->getWord()->getValue())),
            $game->getWord()->getDisplay()
        );
    }

    public function testGuessingCorrectLetter()
    {
        $game = new HangmanGame('secret', 6);
        self::assertFalse($game->isGameOver());
        self::assertEquals(6, $game->getRemainingAttempts());
        $game->playTurn(new Letter('s'));
        $this->assertEquals(6, $game->getRemainingAttempts());
        $this->assertEquals('s_____', $game->getWord()->getDisplay());
    }

    public function testGuessingIncorrectLetter()
    {
        $game = new HangmanGame('hello', 6);
        $game->playTurn(new Letter('z'));

        $this->assertEquals(5, $game->getRemainingAttempts());
        $this->assertEquals('_____', $game->getWord()->getDisplay());
    }

    public function testGuessingSameLetter()
    {
        $game = new HangmanGame('hello', 6);
        $game->playTurn(new Letter('o'));
        $this->assertEquals(6, $game->getRemainingAttempts());
        $game->playTurn(new Letter('o')); // Guessing the same letter twice
        $this->assertEquals(6, $game->getRemainingAttempts()); // Attempts shouldn't change
        $this->assertEquals('____o', $game->getWord()->getDisplay()); // Word representation shouldn't change
    }

        public function testWinningTheGame()
    {
        $game = new HangmanGame('hello',4);

        $game->playTurn(new Letter('h'));
        $game->playTurn(new Letter('e'));
        $game->playTurn(new Letter('l'));
        $game->playTurn(new Letter('o'));
        $this->assertEquals(4, $game->getRemainingAttempts());
        // winning msg
        self::assertStringContainsString(
            sprintf('Congratulations! You guessed the word: %s', $game->getWord()->getValue()),
            $game->getResultMessage()
        );
    }

    public function testLosingTheGame()
    {
        $game = new HangmanGame('world', 4);
        $game->playTurn(new Letter('x'));
        $game->playTurn(new Letter('y'));
        $game->playTurn(new Letter('z'));
        $game->playTurn(new Letter('p'));

        $this->assertEquals(0, $game->getRemainingAttempts()); // All attempts used
        // loosing msg
        self::assertStringContainsString(
            sprintf('You ran out of guesses. The word was: %s', $game->getWord()->getValue()),
            $game->getResultMessage()
        );
    }


//    public function testStartingMultiplayerGame()
//    {
//        $game = new HangmanGame(true); // Enable multiplayer mode
//
//        $this->assertCount(2, $game->getPlayers()); // Assuming default players are 2
//    }
//    public function testMultiPlayerTakingTurns()
//    {
//        $game = new HangmanGame(true);
//        $player1 = $game->getCurrentPlayer(); // Assuming initial player is 1
//
//        $game->guess(new Letter('h')); // Simulate guess by player 1
//
//        $this->assertNotSame($player1, $game->getCurrentPlayer()); // Verify turn switched to player 2
//
//        $game->guess(new Letter('e')); // Simulate guess by player 2
//
//        $this->assertSame($player1, $game->getCurrentPlayer()); // Verify turn back to player 1
//    }
//
//    public function testSkippingPlayerWithNoAttempts()
//    {
//        $prophet = new Prophet();
//        $player1 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');
//        $player2 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');
//
//        $game = new HangmanGame(true); // Enable multiplayer mode
//        $game->setPlayers([$player1->reveal(), $player2->reveal()]); // Mock players
//
//        $player1->getRemainingAttempts()->willReturn(0); // Mock player 1 with no attempts
//
//        $game->guess(new Letter('x')); // Simulate guess (doesn't matter who guesses)
//
//        $prophet->checkPredictions();
//        $this->assertSame($player2->reveal(), $game->getCurrentPlayer()); // Verify turn switched to player 2
//    }
//
//    public function testWinningInMultiplayer()
//    {
//        $prophet = new Prophet();
//        $player1 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');
//        $player2 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');
//
//        $game = new HangmanGame(true); // Enable multiplayer mode
//        $game->setPlayers([$player1->reveal(), $player2->reveal()]); // Mock players
//
//        // Simulate player 1 guessing all letters correctly
//        $game->guess(new Letter('h'));
//        $game->guess(new Letter('e'));
//        $game->guess(new Letter('l'));
//        $game->guess(new Letter('o')); // Winning guess for player 1
//
//        $this->assertTrue($game->isGameOver()); // Verify game is over
//        $this->assertSame($player1->reveal(), $game->getWinningPlayer()); // Verify winning player
//
//        $prophet->checkPredictions();
//    }
//
//    public function testLosingInMultiplayer()
//    {
//        $prophet = new Prophet();
//        $player1 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');
//        $player2 = $prophet->prophesize('App\Domain\Entity\PlayerInterface');
//
//        $game = new HangmanGame(true); // Enable multiplayer mode
//        $game->setPlayers([$player1->reveal(), $player2->reveal()]); // Mock players
//
//        // Simulate player 1 and player 2 using all attempts with incorrect guesses
//        $game->guess(new Letter('x'), $player1->reveal());
//        $game->guess(new Letter('y'), $player1->reveal());
//        $game->guess(new Letter('z'), $player1->reveal());
//        $game->guess(new Letter('p'), $player2->reveal());
//        $game->guess(new Letter('q'), $player2->reveal());
//
//        $this->assertTrue($game->isGameOver()); // Verify game is over
//        $this->assertEquals(0, $player1->getRemainingAttempts()); // Verify player 1 attempts are zero
//        $this->assertEquals(0, $player2->getRemainingAttempts()); // Verify player 2 attempts are zero
//
//        $prophet->checkPredictions();
//    }

//    public function testLosingTurnInMultiplayer()
//    {
//        $game = new HangmanGame(true);
//        $player1 = $game->getPlayer(1); // Assuming players are indexed
//
//        $game->guess(new Letter('x'), $player1); // Simulate player 1 guess
//
//        $player2 = $game->getCurrentPlayer();
//        $this->assertNotSame($player1, $player2); // Verify turn switched to player 2
//        $this->assertEquals(2, $player1->getRemainingAttempts()); // Assuming initial attempts is 3
//    }
}