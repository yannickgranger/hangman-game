<?php

declare(strict_types=1);

namespace App\Tests\Cli\Integration;

use App\Domain\Entity\HangmanGame;
use App\Domain\Entity\Word;
use App\Domain\ValueObject\Letter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class HintTest extends KernelTestCase
{
    private int $maxAttempts;

    protected function setUp(): void
    {
        parent::setUp();
        $container = self::getContainer();
        $this->maxAttempts = $container->getParameter('max_attempts');
    }

    public function testRequestingSingleLetterHint()
    {
        $game = new HangmanGame(Uuid::v4(), new Word('magic'), 6, 5);
        $game->requestHint();

        $decreasedGuess = count($game->getWord()->getRevealedLetters());
        $this->assertEquals($this->maxAttempts - $decreasedGuess, $game->getRemainingAttempts());

        $revealed = $game->getWord()->getRevealedLetters();
        $letter = array_pop($revealed);
        $positions = [];
        $word = 'magic';
        for ($i = 0; $i < strlen($word); $i++) {
            if ($word[$i] === $letter) {
                $positions[] = $i;
            }
        }

        $position = $positions[0];
        $myWord = '';
        for($i = 0; $i < strlen($word); $i++) {
            if($i === $position) {
                $myWord .= $letter;
            } else {
                $myWord .= '_';
            }
        }

        $this->assertEquals($myWord, $game->getWord()->getDisplay());
    }
    public function testRequestingSingleLetterHintFromAnanas()
    {
        $word = 'ananas';
        $game = new HangmanGame(Uuid::v4(), new Word($word), 6, 5);
        $game->playTurn(new Letter('a'));
        $game->playTurn(new Letter('s'));
        $remainingGuesses = $game->getRemainingAttempts();
        $game->requestHint();

        $hintLetterDiff = array_diff($game->getWord()->getRevealedLetters(), ['a', 's']);
        $hintLetter = array_pop($hintLetterDiff);
        for ($i = 0; $i < strlen($word); $i++) {
            if ($word[$i] === $hintLetter) {
                $positions[] = $i;
            }
        }

        $this->assertEquals($remainingGuesses - count($positions), $game->getRemainingAttempts());

        $revealed = $game->getWord()->getRevealedLetters();
        $myWord = '';
        for ($i = 0; $i < strlen($word); $i++) {
            foreach ($revealed as $letter) {
                if ($word[$i] === $letter) {
                    $myWord .= $letter;
                }
            }
        }
        $this->assertEquals($myWord, $game->getWord()->getDisplay());
    }
}
