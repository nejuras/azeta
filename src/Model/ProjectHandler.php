<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Project as ProjectEntity;
use App\Model\Event\GroupWasCreated;
use App\Model\Event\SavableTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProjectHandler implements MessageHandlerInterface
{
    use SavableTrait;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(Project $message): ProjectEntity
    {
        return $this->createProject($message);
    }

    public function createProject($message): ProjectEntity
    {
        $data = $message->getData();

        $project = (new ProjectEntity())
            ->setTitle($data['title'])
            ->setNumberOfGroups($data['numberOfGroups'])
            ->setStudentsPerGroup($data['studentsPerGroup']);

        $this->save($project);

        $this->dispatchEvent($project);

        return $project;
    }

    public function dispatchEvent(ProjectEntity $project): void
    {
        $event = new GroupWasCreated($project);

        $this->eventDispatcher->dispatch($event, GroupWasCreated::GROUP_CREATED);
    }
}
