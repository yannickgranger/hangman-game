<?php

namespace App\Domain\Exception;

class GameFullException extends CannotJoinGameException
{
    public function __construct(string $message = '', int $code = 400, ?\Throwable $previous = null)
    {
        parent::__construct('Game reached maximum player limit, can not join this game', $code, $previous);
    }
}
