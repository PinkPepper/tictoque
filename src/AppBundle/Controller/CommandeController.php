<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Commande controller.
 *
 * @Route("/commande")
 */
class CommandeController extends Controller
{
    /**
     * Choisir ses options de commande
     * @Route("/", name="commande_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        //TODO localisation
        //TODO moyen de paiement


        return $this->render('frontoffice/commande/index.html.twig', array(
        ));
    }


    /**
     * Succes de la commande
     * @Route("/success", name="commande_succes")
     * @Method("GET")
     */
    public function SuccesAction(Request $request)
    {
        //TODO informer le livreur
        //TODO mettre à jour la quantité de produit dans la base selon la commande

        /* Enregistrer les menus */
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $panier = $session->get('panier');

        /* On crée les menus dans la base */
        foreach($panier['menus'] as $panier)
        {
            $menu = new Menu();

            $menu->setMenu($panier['entree'], $panier['plat'], $panier['dessert'], $panier['boisson'], $panier['prix'], $panier['quantite'], $this->getUser());
            $em->persist($menu);
            $em->flush();
        }

        /* Créer un historique de commande */


        //TODO reset le panier

        return $this->render('frontoffice/commande/succes.html.twig', array(
        ));
    }
}
