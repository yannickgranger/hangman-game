<?php

declare(strict_types=1);

namespace App\Domain\Exception;

/**
 * @throws \LogicException
 *                         Used when an invalid guess is provided
 */
class InvalidGuessException extends \LogicException
{
}
