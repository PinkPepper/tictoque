<?php

namespace AppBundle\Listener;

use AppBundle\Entity\Livraison;
use AppBundle\Entity\Produit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel;

class ProduitListener implements EventSubscriber
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            'preRemove'
        );
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $manager = $this->container->get("doctrine")->getManager();
        if($entity instanceof  Produit){

            $categories = $entity->getCategories();
            foreach($categories as $cat)
            {
                $cat->removeProduit($entity);
                $entity->removeCategorie($cat);
            }

            $allergenes = $entity->getAllergenes();
            foreach($allergenes as $all)
            {
                $all->removeProduit($entity);
                $entity->removeAllergene($all);
            }


            $points_relais = $entity->getPointRelais();
            foreach($points_relais as $pr)
            {
                $pr->removeProduit($entity);
                $entity->removePointRelais($pr);

                $manager->persist($entity);
                $manager->persist($entity);
            }

            $manager->flush();
        }
    }
}