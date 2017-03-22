<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commande;
use AppBundle\Entity\CommandeMenu;
use AppBundle\Entity\CommandeProduit;
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
        $em = $this->getDoctrine()->getManager();

        //TODO informer le livreur
        //TODO mettre à jour la quantité de produit dans la base selon la commande

        /* Création de la commande */
        $commande = new Commande();
        $commande->setUser($this->getUser());
        $em->persist($commande);
        $em->flush();

            //TODO vérifier lien dans l'autre sens (user->getCommande())

        /* Récupération du panier */
        $session = $request->getSession();
        $panier = $session->get('panier');

        dump($panier);

        /* On crée les menus dans la base */
        foreach($panier['menus'] as $unMenu)
        {
            $menu = new Menu();

            $menu->setMenu($unMenu['entree'], $unMenu['plat'], $unMenu['dessert'], $unMenu['boisson'], $unMenu['prix'], $unMenu['quantite'], $this->getUser());
            $em->persist($menu);
            $em->flush();

            /* Création commandeMenu */
            $commandeMenu = new CommandeMenu();
            $commandeMenu->setCommande($commande);
            $commandeMenu->setMenus($menu);
            $em->persist($commandeMenu);
            $em->flush();
        }

        /* On récupère les produits du panier */
        foreach($panier['produits'] as $produit)
        {
            $commandeProduit = new CommandeProduit();
            $commandeProduit->setCommande($commande);
            $commandeProduit->setProduits($em->getRepository('AppBundle:Produit')->find($produit[0]));
            $commandeProduit->setQuantiteCommandee($produit[1]);
            $em->persist($commandeProduit);
            $em->flush();
        }

        $em->flush();

        /* Créer un historique de commande */

        //TODO reset le panier

        return $this->render('frontoffice/commande/succes.html.twig', array(
        ));
    }
}
