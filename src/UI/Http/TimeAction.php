<?php

declare(strict_types=1);

namespace App\UI\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/time', name: 'time', methods: ['GET'])]
class TimeAction
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(new \DateTime('now'), Response::HTTP_OK);
    }
}
