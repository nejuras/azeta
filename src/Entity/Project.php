<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $numberOfGroups = null;

    #[ORM\Column]
    private ?int $studentsPerGroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNumberOfGroups(): ?int
    {
        return $this->numberOfGroups;
    }

    public function setNumberOfGroups(int $numberOfGroups): self
    {
        $this->numberOfGroups = $numberOfGroups;

        return $this;
    }

    public function getStudentsPerGroup(): ?int
    {
        return $this->studentsPerGroup;
    }

    public function setStudentsPerGroup(int $studentsPerGroup): self
    {
        $this->studentsPerGroup = $studentsPerGroup;

        return $this;
    }
}
