<?php

declare(strict_types=1);

namespace App\Tests\Cli\Integration;

use App\Domain\Entity\HangmanGame;
use App\Domain\Entity\Word;
use App\Domain\ValueObject\Letter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CliPlayTest extends TestCase
{
    public function testNewGameInitialization()
    {
        $game = new HangmanGame(Uuid::v4(), new Word('test'), 6, 5);
        $this->assertNotEmpty($game->getWord());
        $this->assertGreaterThan(0, $game->getRemainingAttempts());
        $this->assertEquals(
            str_repeat(
                '_',
                strlen($game->getWord()->getValue())
            ),
            $game->getWord()->getDisplay()
        );
    }

    public function testGuessingCorrectLetter()
    {
        $game = new HangmanGame(Uuid::v4(), new Word('secret'), 6, 5);
        self::assertFalse($game->isGameOver());
        self::assertEquals(6, $game->getRemainingAttempts());
        $game->playTurn(new Letter('s'));
        $this->assertEquals(6, $game->getRemainingAttempts());
        $this->assertEquals('s_____', $game->getWord()->getDisplay());
    }

    public function testGuessingIncorrectLetter()
    {
        $game = new HangmanGame(Uuid::v4(), new Word('hello'), 6, 5);
        $game->playTurn(new Letter('z'));

        $this->assertEquals(5, $game->getRemainingAttempts());
        $this->assertEquals('_____', $game->getWord()->getDisplay());
    }

    public function testGuessingSameLetter()
    {
        $game = new HangmanGame(Uuid::v4(), new Word('hello'), 6, 5);
        $game->playTurn(new Letter('o'));
        $this->assertEquals(6, $game->getRemainingAttempts());
        $game->playTurn(new Letter('o')); // Guessing the same letter twice
        $this->assertEquals(6, $game->getRemainingAttempts()); // Attempts shouldn't change
        $this->assertEquals('____o', $game->getWord()->getDisplay()); // Word representation shouldn't change
    }

    public function testWinningTheGame()
    {
        $game = new HangmanGame(Uuid::v4(), new Word('hello'), 4, 5);

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

    public function testLoosingTheGame()
    {
        $game = new HangmanGame(Uuid::v4(), new Word('world'), 4, 5);
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
}
