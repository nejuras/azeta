<?php

declare(strict_types=1);

namespace App\Model\Event;

use App\Entity\Project;
use Symfony\Contracts\EventDispatcher\Event;

class GroupWasCreated extends Event
{
    public const GROUP_CREATED = 'group.created';

    public function __construct(
        private readonly Project $project,
    ) {
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}
