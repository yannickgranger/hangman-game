<?php

declare(strict_types=1);

namespace App\Domain\Exception;

/**
 * This exception belongs to domain
 * It means that your (business) logic went wrong
 * In previous use case, business wanted game to be persistent
 */
class GameNotFoundException extends \LogicException
{
}
