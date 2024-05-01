<?php

declare(strict_types=1);

namespace App\UI\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: "game", name: "start_game", methods: ["POST"])]
final class CreateGameAction
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse();
    }
}