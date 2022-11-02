<?php

declare(strict_types=1);

namespace App\Model\Event;

use Doctrine\ORM\EntityManagerInterface;

trait SavableTrait
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function save($object): void
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
