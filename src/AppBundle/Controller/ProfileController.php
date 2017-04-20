<?php


namespace AppBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    /**
     * Show the user.
     */
    public function showAction()
    {
        $entree = null;
        $plat = null;
        $dessert = null;
        $boisson = null;

       $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $array = array();

        $commandes = $user->getCommandes();

        foreach ($commandes as $commande)
        {
            $commandesMenu = $commande->getCommandesMenu();

            foreach ( $commandesMenu as $commandeMenu)
            {

                if($commandeMenu->getMenus()->getEntree() != null)
                {
                    $entree = $em->find($commandeMenu->getMenus()->getEntree());
                }
                if($commandeMenu->getMenus()->getPlat() != null)
                {
                    $plat = $em->find($commandeMenu->getMenus()->getPlat());
                }
                if($commandeMenu->getMenus()->getDessert() != null)
                {
                    $dessert = $em->find($commandeMenu->getMenus()->getDessert());
                }
                if($commandeMenu->getMenus()->getBoisson() != null)
                {
                    $boisson = $em->find($commandeMenu->getMenus()->getBoisson());
                }

                array_push($array, array('id_commandeMenu'=>$commandeMenu->getId(), 'entree'=>$entree, 'plat'=>$plat, 'dessert'=>$dessert, 'boisson'=>$boisson));
            }
        }

        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user,
            'array'=>$array
        ));
    }



}
