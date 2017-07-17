<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Allergene;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\Produit;
use AppBundle\Entity\ProduitHomePage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Produit controller.
 *
 * @Route("/admin/produit")
 */
class ProduitAdminController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="produit_index_admin")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine();
        $produitsHP = $em->getRepository('AppBundle:ProduitHomePage')->findAll();
        $produits = $em->getRepository('AppBundle:Produit')->findAll();

        if($produitsHP == array())
        {
            $homepage = array();
        }
        else{
            $homepage = array(
                $em->getRepository('AppBundle:Produit')->find($produitsHP[0]->getProduit1()),
                $em->getRepository('AppBundle:Produit')->find($produitsHP[0]->getProduit2()),
                $em->getRepository('AppBundle:Produit')->find($produitsHP[0]->getProduit3()));
        }

        return $this->render('backoffice/admin/produit/index.html.twig', array(
            'produits' => $produits,
            'homepage'=>$homepage
        ));
    }

    /**
     * Creates a new produit entity.
     *
     * @Route("/new", name="produit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm('AppBundle\Form\ProduitCreationType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           // $produit->setAllergenes(null);

            $tmp = $form["cat"]->getData();
            for ($i=0; $i< sizeof($tmp); $i++)
            {
                $produit->addCategorie($tmp[$i]);
                $tmp[$i]->addProduit($produit);

                $this->getDoctrine()->getManager()->persist($tmp[$i]);
                $this->getDoctrine()->getManager()->flush();
            }

            $tmp = $form["all"]->getData();
            for ($i=0; $i< sizeof($tmp); $i++)
            {
                $produit->addAllergene($tmp[$i]);
                $tmp[$i]->addProduit($produit);

                $this->getDoctrine()->getManager()->persist($tmp[$i]);
                $this->getDoctrine()->getManager()->flush();
            }

            $tmp = $form["pr"]->getData();
            for ($i=0; $i< sizeof($tmp); $i++)
            {
                $produit->addPointRelais($tmp[$i]);
                $tmp[$i]->addProduit($produit);
                $this->getDoctrine()->getManager()->persist($tmp[$i]);
                $this->getDoctrine()->getManager()->flush();
            }
            //TODO probleme catégories
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_show_admin', array('id' => $produit->getId()));
        }

        return $this->render('backoffice/admin/produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/produit/homepage/new", name="produit_homepage_new")
     * @Method({"GET", "POST"})
     */
    public function newProduitHomepageAction(Request $request)
    {
        $produitHP = new ProduitHomePage();
        $form = $this->createForm('AppBundle\Form\ProduitHomePageType', $produitHP);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->persist($produitHP);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("produit_index_admin");
        }

        return $this->render("backoffice/admin/produit/newHP.html.twig", array(
            "form"=>$form->createView()
        ));
    }

    /**
     * @Route("/produit/homepage/edit", name="produit_homepage_edit")
     * @Method({"GET", "POST"})
     */
    public function editProduitHomepageAction(Request $request)
    {
        $produitHP = $this->getDoctrine()->getRepository('AppBundle:ProduitHomePage')->findAll();

        if($produitHP == array())
        {
            return $this->redirectToRoute("produit_homepage_new");
        }

        $produitHP = $produitHP[0];

        $form = $this->createForm('AppBundle\Form\ProduitHomePageType', $produitHP);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           /* $this->getDoctrine()->getManager()->persist($produitHP);*/
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("produit_homepage_edit");
        }

        return $this->render("backoffice/admin/produit/editHP.html.twig", array(
            "form"=>$form->createView()
        ));
    }


    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="produit_show_admin")
     * @Method("GET")
     */
    public function showAction(Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);

        return $this->render('backoffice/admin/produit/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/{id}/edit", name="produit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('AppBundle\Form\ProduitType', $produit);
        $editForm->handleRequest($request);

        $points_relais = $produit->getPointRelais();

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $tmp = $editForm["pr"]->getData();
            if($tmp != new ArrayCollection())
            {
                foreach ($produit->getPointRelais() as $pointRelais){
                    $produit->removePointRelais($pointRelais);
                }

                for ($i=0; $i< sizeof($tmp); $i++)
                {
                    $produit->addPointRelais($tmp[$i]);
                    $tmp[$i]->addProduit($produit);
                    $this->getDoctrine()->getManager()->persist($tmp[$i]);
                    $this->getDoctrine()->getManager()->flush();
                }

            }
            else{
                $produit->setPointRelais($points_relais);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_edit', array('id' => $produit->getId()));
        }

        $allergeneForm = $this->createForm('AppBundle\Form\AllergeneType');
        $allergeneForm->handleRequest($request);

        if ($allergeneForm->isSubmitted() && $allergeneForm->isValid()) {
            if($produit->getAllergenes() === null )
            {
                $produit->setAllergenes(array());
            }

            $produit->setAllergenes(array_unique(array_merge(array($allergeneForm->getData()['nom']), $produit->getAllergenes())));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_edit', array('id' => $produit->getId()));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $categories = $em->getRepository('AppBundle\Entity\Categorie')->findAll();
        $allergenes = $em->getRepository('AppBundle\Entity\Allergene')->findAll();

        return $this->render('backoffice/admin/produit/edit.html.twig', array(
            'categories' => $categories,
            'allergenes'=> $allergenes,
            'produit' => $produit,
            'allergeneForm'=>$allergeneForm->createView(),
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * @Route("/ajout/{produit}/{categorie}", name="ajout_categorie_produit")
     */
    public function ajouterCategorie(Request $request, Produit $produit, Categorie $categorie)
    {
        $produit->addCategorie($categorie);
        $categorie->addProduit($produit);

        $this->getDoctrine()->getEntityManager()->persist($categorie);
        $this->getDoctrine()->getEntityManager()->flush();

       return $this->redirectToRoute('produit_edit', array('id'=>$produit->getId()));
    }



    /**
     * @Route("/supprimer/{produit}/{categorie}", name="supprimer_categorie_produit")
     */
    public function supprimerCategorie(Request $request, Produit $produit, Categorie $categorie)
    {
        $produit->removeCategorie($categorie);
        $categorie->removeProduit($produit);

        $this->getDoctrine()->getEntityManager()->flush();

        return $this->redirectToRoute('produit_edit', array('id'=>$produit->getId()));

    }


    /**
     * @Route("/ajout/all/{produit}/{allergene}", name="ajout_allergene_produit")
     */
    public function ajouterAllergene(Request $request, Produit $produit, Allergene $allergene)
    {
        $produit->addAllergene($allergene);
        $allergene->addProduit($produit);

        $this->getDoctrine()->getEntityManager()->persist($allergene);
        $this->getDoctrine()->getEntityManager()->flush();

        return $this->redirectToRoute('produit_edit', array('id'=>$produit->getId()));
    }


    /**
     * @Route("/supprimer/all/{produit}/{allergene}", name="supprimer_allergene_produit")
     */
    public function supprimerAllergene(Request $request, Produit $produit, Allergene $allergene)
    {
        $produit->removeAllergene($allergene);
        $allergene->removeProduit($produit);

        $this->getDoctrine()->getEntityManager()->flush();

        return $this->redirectToRoute('produit_edit', array('id'=>$produit->getId()));

    }

    /**
     * Deletes a produit entity.
     *
     * @Route("/{id}", name="produit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Produit $produit)
    {
        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $categories = $produit->getCategories();
            foreach($categories as $cat)
            {
                $cat->removeProduit($produit);
                $produit->removeCategorie($cat);
            }

            $allergenes = $produit->getAllergenes();
            foreach($allergenes as $all)
            {
                $all->removeProduit($produit);
                $produit->removeAllergene($all);
            }

            $this->getDoctrine()->getEntityManager()->flush();
            $em->remove($produit);
            $em->flush($produit);
        }

        return $this->redirectToRoute('produit_index_admin');
    }

    /**
     * @Route("/delete/delete/{id}", name="produit_delete_index")
     */
    public function deleteIndexAction(Request $request, Produit $produit)
    {
        $categories = $produit->getCategories();
        foreach($categories as $cat)
        {
            $cat->removeProduit($produit);
            $produit->removeCategorie($cat);
        }

        $allergenes = $produit->getAllergenes();
        foreach($allergenes as $all)
        {
            $all->removeProduit($produit);
            $produit->removeAllergene($all);
        }

        $this->getDoctrine()->getEntityManager()->flush();

        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush($produit);

        return $this->redirectToRoute('produit_index_admin');
    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
