<?php

declare(strict_types=1);

namespace App\Tests\unit;

use App\Model\Event\GroupEventSubscriber;
use App\Model\Event\GroupWasCreated;
use App\Model\Project;
use App\Entity\Project as ProjectEntity;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class GroupEventSubscriberTest extends TestCase
{
    use ProphecyTrait;

    private Project|ObjectProphecy $project;

    private EntityManagerInterface|ObjectProphecy $entityManager;

    private GroupEventSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);

        $this->project = $this->prophesize(Project::class);

        $this->subscriber = new GroupEventSubscriber(
            $this->entityManager->reveal(),
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function testShouldCreateGroupOnProjectCreation(): void
    {
        $event = new GroupWasCreated($this->createProject());
        $this->subscriber->onProjectCreation($event);
    }

    /**
     * @throws \ReflectionException
     */
    private function createProject(): ProjectEntity
    {
        $project = (new ProjectEntity())
            ->setTitle('title')
            ->setNumberOfGroups(5)
            ->setStudentsPerGroup(2);

        (new \ReflectionClass(ProjectEntity::class))
            ->getProperty('id')
            ->setValue($project, 2);

        return $project;
    }
}
