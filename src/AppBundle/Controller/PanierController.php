<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    public function indexAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine();
        $panier = $em->getRepository("AppBundle:Panier")->find($user->getPanier());

        return $this->render('frontoffice/panier/index.html.twig', array('panier'=>$panier, 'user'=>$user));
    }
}