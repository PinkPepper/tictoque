<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class PanierController
 * @package AppBundle\Controller
 * @Route("/panier")
 */
class PanierController extends Controller
{
    /**
     * @Route("/", name="index_panier")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine();
        
        $session = $request->getSession();
        $panier = $session->get('panier');

        dump($panier);

        $menus_id = $panier['menus'];
        $produits_id = $panier['produits'];

        $menus = array();
        $produits = array();

        if($menus_id)
        {
            foreach ($menus_id as $menu)
            {
                $m = $em->getRepository("AppBundle:Menu")->find($menu[0]);
                $menu = array($m, $menu[1]);
                array_push($menus, $menu);
            }
        }

        if($produits_id)
        {
            foreach ($produits_id as $produit)
            {
                $produit = array($em->getRepository("AppBundle:Produit")->find($produit[0]), $produit[1]);
                array_push($produits, $produit);
            }
        }

        return $this->render('frontoffice/panier/index.html.twig', array('panier'=>$panier, 'user'=>$user, 'produits'=>$produits, 'menus'=>$menus));
    }
}