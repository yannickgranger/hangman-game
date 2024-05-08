<?php

declare(strict_types=1);

namespace App\Tests\Hexagon\Functional;

use App\Domain\Entity\HangmanGame;
use App\Domain\Entity\Player;
use App\Domain\Entity\Word;
use App\Domain\ValueObject\Letter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class MultiplayerTest extends TestCase
{
    private HangmanGame $game;

    protected function setUp(): void
    {
        parent::setUp();
        $this->game = new HangmanGame(
            Uuid::v4(),
            new Word('secret'),
            6,
            5,
            true,
            2
        );
    }

    public function testJoinGameAndSeeWord()
    {
        $playerName = 'Alice';
        $this->game->join(new Player(Uuid::v4(), $playerName));
        $expectedMessage = 'Welcome to Hangman! The word has 6 letters.\n_____ \nYou have 6 guesses left.';
        $this->assertEquals($expectedMessage, $this->game->getResultMessage()); // Use getResultMessage for all messages
        $this->assertEquals('_____', $this->game->getWord()->getDisplay());
    }

    public function testSecondPlayerJoinsAndSeesWord()
    {
        $player1Name = 'Alice';
        $player2Name = 'Bob';

        $this->game->join(new Player(Uuid::v4(), $player1Name));
        $expectedMessage = 'Welcome to Hangman! The word has 6 letters.\n_____ \nYou have 6 guesses left.';
        $this->assertEquals($expectedMessage, $this->game->getResultMessage());

        $this->game->join(new Player(Uuid::v4(), $player2Name));

        // New player join message with username (visible to both players)
        $expectedJoinMessage = sprintf('Player %s has joined the game!', $player2Name);
        $this->assertEquals($expectedJoinMessage, $this->game->getResultMessage());
        $this->assertEquals('_____', $this->game->getWord()->getDisplay()); // Player 2 should see the word
    }

    public function testGuessLetterUpdateWord()
    {
        $playerName = 'Alice';
        $player = new Player(Uuid::v4(), $playerName);
        $this->game->join($player);

        $this->game->playTurn(new Letter('s'), $player);
        $expectedMessage = 'Good guess! The letter s is in the word.';
        $this->assertEquals($expectedMessage, $this->game->getResultMessage()); // Use getResultMessage for feedback

        $this->assertEquals('s_____', $this->game->getWord()->getDisplay());
    }

    public function testGameOverForWinningPlayer()
    {
        $playerName = 'Alice';
        $player = new Player(Uuid::v4(), $playerName);
        $this->game->join($player);
        $this->game->playTurn(new Letter('s'), $player);
        $this->game->playTurn(new Letter('e'), $player);
        $this->game->playTurn(new Letter('c'), $player);
        $this->game->playTurn(new Letter('r'), $player);
        $this->game->playTurn(new Letter('t'), $player);

        $this->assertEquals('Congratulations! You guessed the word: secret', $this->game->getResultMessage()); // Use getResultMessage for game over
    }

    public function testJoinGameOverflow()
    {
        $player1Name = 'Alice';
        $player2Name = 'Bob';
        $player3Name = 'Charlie';

        $player1 = new Player(Uuid::v4(), $player1Name);
        $this->game->join($player1);

        $player2 = new Player(Uuid::v4(), $player2Name);
        $this->game->join($player2);

        $player3 = new Player(Uuid::v4(), $player3Name);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Game already full');

        $this->game->join($player3);
    }
}
