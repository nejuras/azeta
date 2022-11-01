<?php

declare(strict_types=1);

namespace App\Tests\unit;

use App\Model\Project;
use App\Entity\Project as ProjectEntity;
use App\Model\ProjectHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ProjectHandlerTest extends TestCase
{
    use ProphecyTrait;

    private EntityManagerInterface|ObjectProphecy $entityManager;

    private ProjectHandler $projectHandler;

    protected function setUp(): void
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);

        $this->projectHandler = new ProjectHandler(
            $this->entityManager->reveal(),
        );
    }

    public function testShouldCreateProject(): void
    {
        $message = new Project([
            'title' => 'project',
            'numberOfGroups' => 5,
            'studentsPerGroup' => 2,
        ]);

        $data = $message->getData();

        $uploadFileStatistics = (new ProjectEntity())
            ->setTitle($data['title'])
            ->setNumberOfGroups($data['numberOfGroups'])
            ->setStudentsPerGroup($data['studentsPerGroup']);

        $result = $this->projectHandler->__invoke($message);

        self::assertInstanceOf(ProjectEntity::class, $result);
        self::assertEquals($uploadFileStatistics, $result);
    }
}
