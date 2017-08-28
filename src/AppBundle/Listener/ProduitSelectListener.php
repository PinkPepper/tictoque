<?php

namespace AppBundle\Listener;

use AppBundle\Entity\Livraison;
use AppBundle\Entity\Produit;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel;

class ProduitSelectListener implements EventSubscriber
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postLoad'
        );
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if($entity instanceof  Produit){
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $session = $this->container->get('session');
            if($user === "anon." || $user->getRoles()[0] == "ROLE_USER") // pas un admin ou un livreur
            {
                $hours = (new \DateTime())->format('H');
                $date = (new \DateTime());

                if($hours >= 10)
                {
                    $date = (new \DateTime())->add(new \DateInterval('P1D'));
                }

                $em = $this->container->get('doctrine.orm.entity_manager');
                $livraisons = $em->getRepository("AppBundle:Livraison")->findBy(
                    array('produit'=>$entity->getId(),
                          'pointRelais'=>$session->get('pointRelais'),
                          /*'statut'=>'livré'*/)); //Bravo Maxime pour ce manque d'intelligence <3
                $livraison = null;

                foreach($livraisons as $livr)
                {
                    if($livr->getDate()->format("yyyy-M-d") === $date->format('yyyy-M-d'))
                    {
                        $livraison = $livr;
                        break;
                    }
                }

               if($livraison === null)
               {
                   $entity->setQuantite(0);
               }
               else
               {
                   $entity->setQuantite($livraison->getQuantiteRestante());
               }
            }
        }
    }

}