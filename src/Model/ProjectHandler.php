<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ProjectHandler implements MessageHandlerInterface
{

    public function __invoke(Project $message): int
    {
        return Response::HTTP_CREATED;
    }
}
