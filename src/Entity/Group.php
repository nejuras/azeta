<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ManyToOne(targetEntity: Project::class, inversedBy: 'id')]
    #[JoinColumn(name: 'project_id', referencedColumnName: 'id')]
    private ?Project $project = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $projectId): self
    {
        $this->project = $projectId;

        return $this;
    }
}
