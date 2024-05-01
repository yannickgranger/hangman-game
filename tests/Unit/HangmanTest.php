<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Model\HangmanGame;
use App\Domain\Model\Word;
use PHPUnit\Framework\TestCase;

class HangmanTest extends TestCase
{
    public function testWordCreation()
    {
        $word = new Word('apple');
        $this->assertEquals('apple', $word->getValue());
        $this->assertEquals([], $word->getRevealedLetters());
    }

    public function testPlayerGuessesCorrectLetter()
    {
        $game = new HangmanGame('apple', 6);
        $this->assertTrue($game->playTurn('a'));
        $this->assertEquals(6, $game->getRemainingAttempts());
        $this->assertEquals('a____', $game->getWord()->getDisplay());
    }

    public function testPlayerGuessesIncorrectLetter()
    {
        $game = new HangmanGame('apple', 6);
        $this->assertFalse($game->playTurn('x'));
        $this->assertEquals(5, $game->getRemainingAttempts());
        $this->assertEquals('_____', $game->getWord()->getDisplay());
    }

    public function testPlayerWinsGame()
    {
        $game = new HangmanGame('apple', 6);
        $game->playTurn('a');
        $game->playTurn('p');
        $game->playTurn('l');
        $game->playTurn('e');
        $this->assertTrue($game->isGameOver());
        $this->assertEquals('apple', $game->getWord()->getDisplay());
        $this->assertTrue($game->getRemainingAttempts() > 0);
    }

    public function testPlayerLosesGame()
    {
        $game = new HangmanGame('apple', 3);
        $game->playTurn('x');
        $game->playTurn('y');
        $game->playTurn('z');
        $this->assertTrue($game->isGameOver());
        $this->assertEquals(0, $game->getRemainingAttempts());
        $this->assertEquals('_____', $game->getWord()->getDisplay());
    }
}
