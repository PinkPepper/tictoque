<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Menu;
use AppBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class MenuController
 * @Route("/menu")
 */
class MenuController extends Controller
{
    /**
     * @Route("/", name="menu")
     */
    public function indexAction(Request $request)
    {
        return $this->render('frontoffice/menu/index.html.twig');
    }

    /**
     * @Route("/choix/{type}", name="menu_choix")
     */
    public function choixMenuAction(Request $request, $type)
    {
        $session = $request->getSession();
        $SESSION_MENU = array();

        $menu = new Menu();
        $this->getUser()->setMenus($menu->getId());
        $menu->setUser($this->getUser());
        $menu->setQuantite(1);

        $menu->setType($type);

        if($type == 1)
        {
            $menu->setPrix(12);
        }
        else if($type == 2)
        {
            $menu->setPrix(14);
        }
        else
        {
            $menu->setPrix(16);
        }

        $SESSION_MENU = array($menu);
        $session->set('menu', $SESSION_MENU);


        if($type == 2) //plat + dessert + boisson
        {
            return $this->redirectToRoute('menu_plat');
        }

        return $this->redirectToRoute('menu_entree');
    }


    /**
     * @Route("/entree", name="menu_entree")
     */
    public function entreeAction(Request $request)
    {
        $session = $request->getSession();

        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $produits = $em->findByType('entree');

        return $this->render('frontoffice/menu/entrees.html.twig', array(
            'produits' => $produits
        ));
    }

    /**
     * @Route("/entree/{id}", name="menu_choix_entree")
     */
    public function choixEntreeAction(Request $request, Produit $entree)
    {
        $session = $request->getSession();
        $menu = $session->get('menu');

        $menu[0]->setEntree($entree->getId());

       return $this->redirectToRoute('menu_plat');
    }

    /**
     * @Route("/plat", name="menu_plat")
     */
    public function platAction(Request $request)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $produits = $em->findByType('plat');

        return $this->render('frontoffice/menu/plats.html.twig', array(
            'produits' => $produits
        ));
    }

    /**
     * @Route("/plat/{id}", name="menu_choix_plat")
     */
    public function choixPlatAction(Request $request, Produit $plat)
    {
        $session = $request->getSession();
        $menu = $session->get('menu');
        $menu[0]->setPlat($plat->getId());

        if($menu[0]->getType() == 1) // entree + plat + boisson
        {
            return $this->redirectToRoute('menu_boisson');
        }

        // plat + dessert + boisson || entree + plat + dessert + boisson
        return $this->redirectToRoute('menu_dessert');
    }

    /**
     * @Route("/dessert", name="menu_dessert")
     */
    public function dessertAction(Request $request)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $produits = $em->findByType('dessert');

        return $this->render('frontoffice/menu/desserts.html.twig', array(
            'produits' => $produits
        ));
    }

    /**
     * @Route("/dessert/{id}", name="menu_choix_dessert")
     */
    public function choixDessertAction(Request $request, Produit $dessert)
    {
        $session = $request->getSession();
        $menu = $session->get('menu');
        $menu[0]->setDessert($dessert->getId());

        return $this->redirectToRoute('menu_boisson');
    }

    /**
     * @Route("/boisson", name="menu_boisson")
     */
    public function boissonAction(Request $request)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $produits = $em->findByType('boisson');

        return $this->render('frontoffice/menu/boissons.html.twig', array(
            'produits' => $produits
        ));
    }

    /**
     * @Route("/boisson/{id}", name="menu_choix_boisson")
     */
    public function choixBoissonAction(Request $request, Produit $boisson)
    {

        $session = $request->getSession();
        $menu = $session->get('menu');
        $menu[0]->setBoisson($boisson->getId());

        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');

        if($menu[0]->getEntree() != null) $entree =  $em->find($menu[0]->getEntree());
        else $entree = null;

        if($menu[0]->getPlat() != null) $plat =  $em->find($menu[0]->getPlat());
        else $plat = null;

        if($menu[0]->getDessert() != null) $dessert =  $em->find($menu[0]->getDessert());
        else $dessert = null;

        if($menu[0]->getBoisson() != null) $boisson =  $em->find($menu[0]->getBoisson());
        else $boisson = null;

        $session->set('menu_' . sizeof($session->all()), array(
            'entree'=>$entree,
            'plat'=>$plat,
            'dessert'=>$dessert,
            'boisson'=>$boisson,
            'quantite'=>1,
            "prix"=>$menu[0]->getPrix()
        ));

        if($session->get('prix') == null)
        {
            $session->set('prix', 5); // 5 -> frais de livraison
            $prix = 5;
        }
        else
        {
            $prix = $session->get('prix');
        }

        $session->set('prix', ($prix + $menu[0]->getPrix()));

        return $this->redirectToRoute('index_panier');
    }
}