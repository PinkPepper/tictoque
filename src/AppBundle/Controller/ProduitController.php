<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Produit;
use JasonGrimes\Paginator;
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

        $paginator  = $this->get('knp_paginator');
        $query = $em->getRepository('AppBundle:Produit')->findAll();
        $maxPerPage = 9;
        $produits = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $maxPerPage/*limit per page*/
        );

        $isAllergene = false;


        $form = $this->createForm('AppBundle\Form\RechercheType');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $isAllergene = $form->getData()['allergene'];

            $search = $form->getData()['search'];
            if($search !== null)
            {
                $produits = $this->recherchePersonnalisee($search, $paginator);
            }
            else
            {
                $produits = $this->rechercheFiltre($form, $paginator, $maxPerPage, $query);
            }

        }

        if($this->getUser() === null)
        {
            $allergenes = array();
        }
        else $allergenes = $this->getUser()->getAllergenes();


        return $this->render('frontoffice/produit/index.html.twig', array(
            'categories' => $categories,
            'categorie' => $categorie,
            'produits' => $produits,
            'form'=>$form->createView(),
            //'form_personnalise'=>$form2->createView()
            'isAllergene'=>$isAllergene,
            'allergenes'=>$allergenes
        ));
    }

    public function recherchePersonnalisee($search, $paginator)
    {
        $repository = $this->getDoctrine()->getManager();

        $query = $repository->createQueryBuilder('p')
            ->select('p')
            ->from('AppBundle:Produit', 'p')
            ->where('p.nom LIKE :word')
            ->setParameter('word', '%'.$search.'%')
            ->getQuery();


        $produits = $paginator->paginate(
            $query, /* query NOT result */
            1/*page number*/,
            sizeof($query->getResult())/*limit per page*/
        );

        return $produits;
    }

    public function rechercheFiltre($form, $paginator, $maxPerPage, $query)
    {
       // $query = null;
        $type = $form->getData()['type'];
        $categorie = $form->getData()['categorie'];


        if($type == 'all' && $categorie->getNom() == 'Tous les produits')
        {
            $produits = $paginator->paginate(
                $query, /* query NOT result */
                1,
                $maxPerPage/*limit per page*/
            );

        }
        else if($type == 'all' || $type === null)
        {
            $str = "SELECT p FROM AppBundle:Produit p LEFT JOIN p.categories c WHERE c = " . $categorie->getId();
            $query = $this->getDoctrine()->getManager()->createQuery($str);

            if($query->getresult() == array())$maxPerPage = 1;
            else $maxPerPage =  sizeof($query->getResult());


            $produits = $paginator->paginate(
                $query, /* query NOT result */
                1/*page number*/,
                $maxPerPage/*limit per page*/
            );
        }
        else
        {
            $str = "SELECT p FROM AppBundle:Produit p LEFT JOIN p.categories c WHERE p.type = '". $type ."' AND c = " . $categorie->getId();
            $query = $this->getDoctrine()->getManager()->createQuery($str);

            if($query->getresult() == array())$maxPerPage = 1;
            else $maxPerPage =  sizeof($query->getResult());

            $produits = $paginator->paginate(
                $query, /* query NOT result */
                1/*page number*/,
                $maxPerPage/*limit per page*/
            );
        }

        return $produits;
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
