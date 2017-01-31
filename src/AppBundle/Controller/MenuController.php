<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        $menu = new Menu();
        $this->getUser()->setMenus($menu->getId());
        $menu->setUser($this->getUser());
        $menu->setQuantite(1);
        $menu->setPrix(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($menu);
        $em->flush($menu);
    }

    /**
     * @Route("/entree/", name="menu_entree")
     */
    public function entreeAction(Request $resquest)
    {

        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
       # $entrees = $em->findBy(array('type'=>'entree'));
        $entrees = $em->findByType('entree');

        return $this->render('produit/entrees.html.twig', array(
            'entrees' => $entrees
        ));
    }

    /**
     * @Route("/plat", name="menu_plat")
     */
    public function platAction(Request $request)
    {

    }
}