<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    public function create(Request $request, SerializerInterface $serializer): Response
    {
        $data = $this->getRequestData($request);

        $result = $this->handle($data);

        return new Response($serializer->serialize($result, 'json'), Response::HTTP_CREATED);
    }

    public function getRequestData(Request $request): Project
    {
        return new Project(json_decode($request->getContent(), true));
    }
}
