<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if($this->getUser() !== null)
        {
            if($this->getUser()->getRoles()[0] == "ROLE_ADMIN")
            {
                return $this->redirectToRoute('admin_index');
            }
        }


        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('AppBundle:Produit')->findAll();
        $articles = $em->getRepository('AppBundle:Article')->findAll();

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


        return $this->render('frontoffice/default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'produits' => $produits,
            'articles' => $articles,
            'homepage' => $homepage
        ]);
    }

    /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function mentionsAction()
    {
        return $this->render("frontoffice/default/mentions.html.twig");
    }

    /**
     * @Route("/conditions-ventes", name="conditions_ventes")
     */
    public function conditionsVentesAction()
    {
        return $this->render("frontoffice/default/conditions.html.twig");
    }

    /**
     * @Route("/qualite-produit", name="qualite_produit")
     */
    public function qualiteProduitAction()
    {
        return $this->render("frontoffice/default/qualite.html.twig");
    }

}
