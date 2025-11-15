<?php

namespace App\Infrastructure\Doctrine\Listener;


use App\Application\Commons\Event\EventPublisher;
use App\Domain\Model\Entity;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class EntityEventsListener
{
    public function __construct(private readonly EventPublisher $eventPublisher)
    {
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->raiseEvents($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->raiseEvents($args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->raiseEvents($args);
    }

    private function raiseEvents(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (is_subclass_of($entity, Entity::class)) {
            foreach ($entity->events() as $event) {
                $this->eventPublisher->publish($event);
            }
        }
    }
}
