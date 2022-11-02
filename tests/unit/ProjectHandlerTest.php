<?php

declare(strict_types=1);

namespace App\Tests\unit;

use App\Model\Event\GroupWasCreated;
use App\Model\Project;
use App\Entity\Project as ProjectEntity;
use App\Model\ProjectHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProjectHandlerTest extends TestCase
{
    use ProphecyTrait;

    private EntityManagerInterface|ObjectProphecy $entityManager;

    private EventDispatcherInterface $eventDispatcher;

    private MessageBusInterface|ObjectProphecy $messageBus;

    private ProjectHandler $projectHandler;

    private array $command = [
        'title' => 'project',
        'numberOfGroups' => 5,
        'studentsPerGroup' => 2,
    ];

    protected function setUp(): void
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->eventDispatcher = new EventDispatcher();
        $this->messageBus = $this->prophesize(MessageBusInterface::class);

        $this->projectHandler = new ProjectHandler(
            $this->entityManager->reveal(),
            $this->eventDispatcher,
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function testShouldCreateProject(): void
    {
        $message = new Project($this->command);

        $data = $message->getData();

        $project = $this->createProject($data);

        $result = $this->projectHandler->__invoke($message);

        self::assertInstanceOf(ProjectEntity::class, $result);
        self::assertEquals($project->getNumberOfGroups(), $result->getNumberOfGroups());
        self::assertEquals($project->getStudentsPerGroup(), $result->getStudentsPerGroup());
    }

    /**
     * @throws \ReflectionException
     */
    public function testShouldDispatchEvent(): void
    {
        $message = new Project($this->command);

        $data = $message->getData();

        $project = $this->createProject($data);

        $dispatchedEvent = $this->dispatchEvent($project);

        $this->projectHandler->dispatchEvent($project);
        $this->assertEquals($project, $dispatchedEvent->getProject());

        $result = $this->projectHandler->__invoke($message);

        self::assertInstanceOf(ProjectEntity::class, $result);
    }

    protected function dispatchEvent($project): GroupWasCreated
    {
        $event = new GroupWasCreated($project);

        return $this->eventDispatcher->dispatch($event);
    }

    /**
     * @throws \ReflectionException
     */
    private function createProject($data): ProjectEntity
    {
        $project = (new ProjectEntity())
            ->setTitle($data['title'])
            ->setNumberOfGroups($data['numberOfGroups'])
            ->setStudentsPerGroup($data['studentsPerGroup']);

        (new \ReflectionClass(ProjectEntity::class))
            ->getProperty('id')
            ->setValue($project, 2);

        return $project;
    }
}
