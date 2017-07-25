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
                $em = $this->container->get('doctrine.orm.entity_manager');
                $livraisons = $em->getRepository("AppBundle:Livraison")->findBy(
                    array('produit'=>$entity->getId(),
                          'pointRelais'=>$session->get('pointRelais'),
                          'statut'=>'livré')); //Bravo Maxime pour ce manque d'intelligence <3
                $livraison = null;

                foreach($livraisons as $livr)
                {
                    if($livr->getDate()->format("yyyy-M-d") === (new \DateTime())->format('yyyy-M-d')) //on regarde si le produit livré est à la date du jour
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
                   $entity->setQuantite($livraison->getQuantite());
               }
            }
        }
    }

}