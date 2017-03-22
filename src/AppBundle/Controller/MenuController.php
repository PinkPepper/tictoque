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

        $em = $this->getDoctrine()->getManager();
        $em->persist($menu);
        $em->flush();

        if($type == 2) //plat + dessert + boisson
        {
            return $this->redirectToRoute('menu_plat', array('menu'=>$menu->getId()));
        }

        return $this->redirectToRoute('menu_entree', array('menu'=>$menu->getId()));
    }


    /**
     * @Route("/entree/{menu}", name="menu_entree")
     */
    public function entreeAction(Request $resquest, Menu $menu)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $produits = $em->findByType('entree');

        return $this->render('frontoffice/menu/entrees.html.twig', array(
            'produits' => $produits,
            'menu' => $menu
        ));
    }

    /**
     * @Route("/entree/{menu}/{id}", name="menu_choix_entree")
     */
    public function choixEntreeAction(Request $request, Menu $menu, Produit $entree)
    {
        $menu->setEntree($entree->getId());

        $em = $this->getDoctrine()->getManager();
        $em->persist($menu);
        $em->flush();

       return $this->redirectToRoute('menu_plat', array('menu'=>$menu));
    }

    /**
     * @Route("/plat/{menu}", name="menu_plat")
     */
    public function platAction(Request $request, Menu $menu)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $produits = $em->findByType('plat');

        return $this->render('frontoffice/menu/plats.html.twig', array(
            'menu' => $menu,
            'produits' => $produits
        ));
    }

    /**
     * @Route("/plat/{menu}/{id}", name="menu_choix_plat")
     */
    public function choixPlatAction(Request $request, Menu $menu, Produit $plat)
    {
        $menu->setPlat($plat->getId());

        $em = $this->getDoctrine()->getManager();
        $em->persist($menu);
        $em->flush();

        if($menu->getType() == 1) // entree + plat + boisson
        {
            //return $this->render('frontoffice/menu/boisson.html.twig');
            return $this->redirectToRoute('menu_boisson', array('menu'=>$menu));
        }

        // plat + dessert + boisson || entree + plat + dessert + boisson
        return $this->redirectToRoute('menu_dessert', array('menu'=>$menu));
    }

    /**
     * @Route("/dessert/{menu}", name="menu_dessert")
     */
    public function dessertAction(Request $request, Menu $menu)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $produits = $em->findByType('dessert');

        return $this->render('frontoffice/menu/desserts.html.twig', array(
            'menu' => $menu,
            'produits' => $produits
        ));
    }

    /**
     * @Route("/dessert/{menu}/{id}", name="menu_choix_dessert")
     */
    public function choixDessertAction(Request $request, Menu $menu, Produit $dessert)
    {
        $menu->setDessert($dessert->getId());

        $em = $this->getDoctrine()->getManager();
        $em->persist($menu);
        $em->flush();

        return $this->redirectToRoute('menu_boisson', array('menu'=>$menu));
    }

    /**
     * @Route("/boisson/{menu}", name="menu_boisson")
     */
    public function boissonAction(Request $request, Menu $menu)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $produits = $em->findByType('boisson');

        return $this->render('frontoffice/menu/boissons.html.twig', array(
            'menu' => $menu,
            'produits' => $produits
        ));
    }

    /**
     * @Route("/boisson/{menu}/{id}", name="menu_choix_boisson")
     */
    public function choixBoissonAction(Request $request, Menu $menu, Produit $boisson)
    {
        $menu->setBoisson($boisson->getId());

        $session = $request->getSession();
        $panier = $session->get('panier');

        if(!$panier)
        {
            $panier = array('menus'=>array(), 'produits'=>array());
        }

        array_push($panier, (array_push($panier['menus'], array("id"=> sizeof($panier['menus']),"entree"=>$menu->getEntree(), "plat"=>$menu->getPlat(), "dessert"=>$menu->getDessert(), "boisson"=>$menu->getBoisson(), "quantite"=>1, "prix"=>$menu->getPrix()))));
        $session->set('panier', $panier);

        dump($panier);


        //return $this->render('frontoffice/panier/index.html.twig', array('panier'=>$panier));
        return $this->redirectToRoute('index_panier');
    }
}