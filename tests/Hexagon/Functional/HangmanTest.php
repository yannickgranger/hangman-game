<?php

declare(strict_types=1);

namespace App\Tests\Hexagon\Functional;

use App\Domain\Entity\HangmanGame;
use App\Domain\Entity\Word;
use App\Domain\ValueObject\Letter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class HangmanTest extends TestCase
{
    public function testWordCreation(): void
    {
        $word = new Word('apple');
        $this->assertEquals('apple', $word->getValue());
        $this->assertEquals([], $word->getRevealedLetters());
    }

    public function testPlayerGuessesCorrectLetter(): void
    {
        $game = new HangmanGame(
            Uuid::v4(),
            new Word('apple'),
            6,
            5
        );
        $this->assertTrue($game->playTurn(new Letter('a')));
        $this->assertEquals(6, $game->getRemainingAttempts());
        $this->assertEquals('a____', $game->getWord()->getDisplay());
    }

    public function testPlayerGuessesIncorrectLetter(): void
    {
        $game = new HangmanGame(
            Uuid::v4(),
            new Word('apple'),
            6,
            5
        );
        $this->assertFalse($game->playTurn(new Letter('x')));
        $this->assertEquals(5, $game->getRemainingAttempts());
        $this->assertEquals('_____', $game->getWord()->getDisplay());
    }

    public function testPlayerWinsGame(): void
    {
        $game = new HangmanGame(
            Uuid::v4(),
            new Word('apple'),
            6,
            5
        );
        $game->playTurn(new Letter('a'));
        $game->playTurn(new Letter('p'));
        $game->playTurn(new Letter('l'));
        $game->playTurn(new Letter('e'));
        $this->assertTrue($game->isGameOver());
        $this->assertEquals('apple', $game->getWord()->getDisplay());
        $this->assertTrue($game->getRemainingAttempts() > 0);
    }

    public function testPlayerLosesGame(): void
    {
        $game = new HangmanGame(
            Uuid::v4(),
            new Word('apple'),
            3,
            5
        );
        $game->playTurn(new Letter('x'));
        $game->playTurn(new Letter('y'));
        $game->playTurn(new Letter('z'));
        $this->assertTrue($game->isGameOver());
        $this->assertEquals(0, $game->getRemainingAttempts());
        $this->assertEquals('_____', $game->getWord()->getDisplay());
    }
}
