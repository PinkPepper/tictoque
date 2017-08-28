<?php

namespace AppBundle\Listener;

use AppBundle\Entity\Livraison;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LivraisonListener implements EventSubscriber
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist'
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if($entity instanceof  Livraison){
            $entity->setQuantiteRestante($entity->getQuantite());
        }
    }

}