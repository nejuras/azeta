<?php

namespace App\Model\Event;

use App\Entity\Group;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GroupEventSubscriber implements EventSubscriberInterface
{
    use SavableTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            GroupWasCreated::GROUP_CREATED => 'onProjectCreation',
        ];
    }

    public function onProjectCreation(GroupWasCreated $event)
    {
        for ($number = 1; $number <= $event->getProject()->getNumberOfGroups(); $number++) {
            $groups = (new Group())
                ->setProject($event->getProject());

            $this->save($groups);
        }
    }
}