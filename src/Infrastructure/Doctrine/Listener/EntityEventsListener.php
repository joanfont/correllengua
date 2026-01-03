<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Listener;

use App\Application\Commons\Event\EventPublisher;
use App\Domain\Model\Entity;

use function assert;

use Doctrine\Persistence\Event\LifecycleEventArgs;

use function is_subclass_of;

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
        if (!is_subclass_of($entity, Entity::class)) {
            return;
        }

        assert($entity instanceof Entity);

        foreach ($entity->events() as $event) {
            $this->eventPublisher->publish($event);
        }
    }
}
