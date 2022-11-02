<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Group;
use App\Entity\Project as ProjectEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProjectReadHandler implements MessageHandlerInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(ProjectRead $message): array
    {
        $project = $this->entityManager->find(ProjectEntity::class, $message->getId());

        if (!$project) {
            throw new ResourceNotFoundException('Project not found.');
        }

        $groups = $this->entityManager->getRepository(Group::class)->findBy(['project' => $project->getId()]);

        return $this->generateProjectList($project, $groups);
    }

    private function generateProjectList($project, $groups): array
    {
        $projectList['project'] = [
            'id' => $project->getId(),
            'title' => $project->getTitle(),
            'numberOfGroups' => $project->getNumberOfGroups(),
            'studentsPerGroup' => $project->getStudentsPerGroup(),
        ];

        $groupList['groups'] = array_map(
            static fn(Group $group) => ['id' => $group->getId()],
            $groups
        );

        return [...$projectList, ... $groupList];
    }
}
