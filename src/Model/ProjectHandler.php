<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Project as ProjectEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ProjectHandler implements MessageHandlerInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Project $message): ProjectEntity
    {
        return $this->createProject($this->entityManager, $message);
    }

    public function createProject(EntityManagerInterface $entityManager, $message): ProjectEntity
    {
        $data = $message->getData();

        $projects = (new ProjectEntity())
            ->setTitle($data['title'])
            ->setNumberOfGroups($data['numberOfGroups'])
            ->setStudentsPerGroup($data['studentsPerGroup']);

        $entityManager->persist($projects);

        $entityManager->flush();

        return $projects;
    }
}
