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
     * @Route("/validation_panier/success", name="commande_succes")
     * @Method("GET")
     */
    public function SuccesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();

        $commande = new Commande();
        $commande->setUser($this->getUser());
        $em->persist($commande);
        $em->flush();

        //TODO informer le livreur
        //TODO mettre à jour la quantité de produit dans la base selon la commande

//        /* Création de la commande */
//        $commande = new Commande();
//        $commande->setUser($this->getUser());
//        $em->persist($commande);
//        $em->flush();
//
//        /* Récupération du panier */
//        $session = $request->getSession();
//        $panier = $session->get('panier');
//
//
//        /* On crée les menus dans la base */
//        if(sizeof($panier['menus']) > 0)
//        {
//            foreach ($panier['menus'] as $unMenu) {
//                if($unMenu->getQuantite != 0)
//                {
//                    $menu = new Menu();
//                    $menu->setMenu($unMenu['entree'], $unMenu['plat'], $unMenu['dessert'], $unMenu['boisson'], $unMenu['prix'], $unMenu['quantite'], $this->getUser());
//
//                    $em->persist($menu);
//                    $em->flush();
//
//                    var_dump("flush menu : " . $menu->getId());
//
//                    /* Création commandeMenu */
//                    $commandeMenu = new CommandeMenu();
//                    $commandeMenu->setCommande($commande);
//                    $commandeMenu->setMenus($menu);
//                    $em->persist($commandeMenu);
//                    $em->flush();
//                }
//            }
//        }
//
//        /* On récupère les produits du panier */
//        if(sizeof($panier['produits']) > 0)
//        {
//            foreach($panier['produits'] as $produit)
//            {
//                if($produit[1] != 0)
//                {
//                    $commandeProduit = new CommandeProduit();
//                    $commandeProduit->setCommande($commande);
//                    $commandeProduit->setProduits($em->getRepository('AppBundle:Produit')->find($produit[0]));
//                    $commandeProduit->setQuantiteCommandee($produit[1]);
//                    $em->persist($commandeProduit);
//                    $em->flush();
//                }
//            }
//        }


        $em = $this->getDoctrine();
        $panier = $session->all();

        $produits = array();
        $menus = array();

        foreach ($panier as $key => $value)
        {
            if (strpos($key, 'produit') !== false) //produit
            {
                $id = explode("_", $key);
                $id = $id[1];
                array_push($produits, array($em->getRepository('AppBundle:Produit')->find($id), $value['quantite']));

                $commandeProduit = new CommandeProduit();
                $commandeProduit->setCommande($commande);
                $commandeProduit->setProduits($em->getRepository('AppBundle:Produit')->find($id));
                $commandeProduit->setQuantiteCommandee($value['quantite']);
                $em->getManager()->persist($commandeProduit);
                $em->getManager()->flush();
            }
            else if ($key != "menu" && (strpos($key, 'menu') !== false)) //menu
            {
                $menu = new Menu();
                $menu->setMenu($value['entree'], $value['plat'], $value['dessert'], $value['boisson'], $value['prix'], $value['quantite'], $this->getUser());

                $em->getManager()->persist($menu);
                $em->getManager()->flush();

                /* Création commandeMenu */
                $commandeMenu = new CommandeMenu();
                $commandeMenu->setCommande($commande);
                $commandeMenu->setMenus($menu);
                $em->getManager()->persist($commandeMenu);
                $em->getManager()->flush();

            }
        }


        /* reset le panier */
        $session = $request->getSession();
        $session->clear();

        return $this->render('frontoffice/commande/succes.html.twig', array(
        ));
    }
}
