<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Produit controller.
 *
 * @Route("/produits")
 */
class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="produit_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Categorie')->findAll();
        $categorie = null;
        $produits = $em->getRepository('AppBundle:Produit')->findAll();

        $form = $this->createForm('AppBundle\Form\RechercheType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $type = $form->getData()['type'];
            $categorie = $form->getData()['categorie'];

            if($categorie == 'tous les produits' || $categorie === null)
            {
                $produits = $em->getRepository('AppBundle:Produit')->findBy(array("type"=>$type));
            }
            else if($type == 'all' || $type === null)
            {
                $res = $em->getRepository('AppBundle:Produit')->findByCategorie($categorie->getId());
                $produits = array();
                foreach ($res as $r)
                {
                    array_push($produits, $em->getRepository('AppBundle:Produit')->find($r['id']));
                }
            }
            else
            {
                $res = $em->getRepository('AppBundle:Produit')->findByTypeAndCategorie($type, $categorie->getId());
                $produits = array();
                foreach ($res as $r)
                {
                    array_push($produits, $em->getRepository('AppBundle:Produit')->find($r['id']));
                }
            }

            if(!$produits) $produits = "Aucun produit disponible";
        }

        return $this->render('frontoffice/produit/index.html.twig', array(
            'categories' => $categories,
            'categorie' => $categorie,
            'produits'=> $produits,
            'form'=>$form->createView()
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
     * Finds and displays a produit entity.
     *
     * @Route("/produit/panier/{id}", name="produit_panier_show")
     * @Method("GET")
     */
    public function showPanierAction(Produit $produit)
    {
        return $this->render('frontoffice/panier/produit.html.twig', array(
            'produit' => $produit
        ));
    }
}
