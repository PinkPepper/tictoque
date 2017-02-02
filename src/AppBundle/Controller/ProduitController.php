<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Produit controller.
 *
 * @Route("/produit")
 */
class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="produit_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('AppBundle:Produit')->findAll();


        return $this->render('frontoffice/produit/index.html.twig', array(
            'produits' => $produits,

        ));
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="produit_show")
     * @Method("GET")
     */
    public function showAction(Produit $produit)
    {
        $autre="";

        if($produit->getType()=='entree'){
            $em = $this->getDoctrine()->getManager();
            $autre = $em->getRepository('AppBundle:Produit')->findByType('plat');
            shuffle($autre);
        }
        if($produit->getType()=='plat'){
            $em = $this->getDoctrine()->getManager();
            $autre = $em->getRepository('AppBundle:Produit')->findByType('dessert');
            shuffle($autre);
        }
        if($produit->getType()=='dessert'){
            $em = $this->getDoctrine()->getManager();
            $autre = $em->getRepository('AppBundle:Produit')->findByType('boisson');
            shuffle($autre);
        }
        if($produit->getType()=='boisson'){
            $em = $this->getDoctrine()->getManager();
            $autre = $em->getRepository('AppBundle:Produit')->findByType('plat');
            shuffle($autre);
        }

        return $this->render('frontoffice/produit/show.html.twig', array(
            'produit' => $produit,
            'autre' => $autre
        ));
    }

    /**
     * @Route("/ajoutPanier/{produit}", name="ajout_panier")
     */
    public function ajouterPanier(Produit $produit)
    {
        $doublon = false;
        $panierProduit=null;

        $em = $this->getDoctrine();
        $panier = $em->getRepository('AppBundle:Panier')->find($this->getUser()->getPanier());

        $produits = $panier->getProduits();
        foreach ($produits as $p)
        {
            if($p == $produit)
            {
                $doublon = true;
                break;
            }
        }

      if($doublon == false)
      {
          $panier->addProduit($produit);
          //TODO mettre quantite à 1
      }
      else
      {
          //TODO modifier la quantite
      }

        $em->getManager()->persist($panier);
        $em->getManager()->flush($panier);

        return $this->render('frontoffice/produit/success.html.twig');
    }
}
