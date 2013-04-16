<?php

namespace Zmc\DemoBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Zmc\DemoBundle\Entity\User;

class OccupationPopularitySubscriber implements EventSubscriber
{
    protected $formSubscriber;

    public function __construct(EventSubscriberInterface $subscriber)
    {
        $this->formSubscriber = $subscriber;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->recalculatePopularity($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->recalculatePopularity($args);
    }
    
    public function recalculatePopularity(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if (!$entity instanceof User) {
            return;
        }

        foreach ($this->formSubscriber->getOccupations() as $occupation) {
            $occupation->setPopularity($occupation->getPopularity() - 1);
            $entityManager->persist($occupation);
        }

        foreach ($entity->getOccupations() as $occupation) {
            $occupation->setPopularity($occupation->getPopularity() + 1);
            $entityManager->persist($occupation);
        }

        $entityManager->flush();
    }
}