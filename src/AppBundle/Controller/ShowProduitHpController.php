<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Produit controller.
 *
 * @Route("/")
 */
class ShowProduitHpController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/produitHomePage", name="produitHomePage")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('AppBundle:Produit')->findAll();
        $produitsHP = $em->getRepository('AppBundle:ProduitHomePage')->findAll();
        if($produitsHP == array())
        {
            $homepage = array($produits[0], $produits[1], $produits[3]);
        }
        else{
            $homepage = array(
                $em->getRepository('AppBundle:Produit')->find($produitsHP[0]->getProduit1()),
                $em->getRepository('AppBundle:Produit')->find($produitsHP[0]->getProduit2()),
                $em->getRepository('AppBundle:Produit')->find($produitsHP[0]->getProduit3()));
        }

        return $this->render('frontoffice/default/produitHomePage.html.twig', [
            'produits' => $produits,
            'homepage' => $homepage
        ]);
    }
}
