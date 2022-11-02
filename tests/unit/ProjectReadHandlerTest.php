<?php

declare(strict_types=1);

namespace App\Tests\unit;

use App\Entity\Project as ProjectEntity;
use App\Model\ProjectRead;
use App\Model\ProjectReadHandler;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProjectReadHandlerTest extends TestCase
{
    use ProphecyTrait;

    private EntityManagerInterface|ObjectProphecy $entityManager;

    private EventDispatcherInterface $eventDispatcher;

    private MessageBusInterface|ObjectProphecy $messageBus;

    private ProjectReadHandler $projectHandler;

    private GroupRepository|ObjectProphecy $groupRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->eventDispatcher = new EventDispatcher();
        $this->messageBus = $this->prophesize(MessageBusInterface::class);
        $this->groupRepository = $this->prophesize(GroupRepository::class);

        $this->projectHandler = new ProjectReadHandler(
            $this->entityManager->reveal(),
            $this->eventDispatcher,
        );

    }

    /**
     * @throws \ReflectionException
     */
    public function testShouldNotReturnProject(): void
    {
        $message = new ProjectRead(3);

        $this->entityManager->find(ProjectEntity::class, $message->getId())->willReturn(null);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('Project not found.');

        $this->projectHandler->__invoke($message);
    }

    /**
     * @throws \ReflectionException
     */
    private function createProject(): ProjectEntity
    {
        $project = (new ProjectEntity())
            ->setTitle('title')
            ->setNumberOfGroups(4)
            ->setStudentsPerGroup(2);

        (new \ReflectionClass(ProjectEntity::class))
            ->getProperty('id')
            ->setValue($project, 2);

        return $project;
    }
}
