<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Letter;
use Symfony\Component\Uid\Uuid;

class Player
{
    private Uuid $id;
    private string $username;
    private array $guesses;

    public function __construct(Uuid $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function updateGuess(Word $word, Letter $letter): void
    {
        $this->guesses[] = [
            'word' => [
                'value' => $word->getValue(),
                'display' => $word->getDisplay(),
                'revealedLetters' => $word->getRevealedLetters(),
            ],
            'letter' => $letter->getValue(),
            'status' => $word->guess($letter),
        ];
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
