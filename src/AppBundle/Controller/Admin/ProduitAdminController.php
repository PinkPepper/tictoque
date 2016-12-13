<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Categorie;
use AppBundle\Entity\Produit;
use Doctrine\Common\Collections\ArrayCollection;
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
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('AppBundle:Produit')->findAll();

        return $this->render('admin/produit/index.html.twig', array(
            'produits' => $produits,
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
        $form = $this->createForm('AppBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit->setAllergenes(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush($produit);

            return $this->redirectToRoute('produit_show_admin', array('id' => $produit->getId()));
        }

        return $this->render('admin/produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
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

        return $this->render('admin/produit/show.html.twig', array(
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

        if ($editForm->isSubmitted() && $editForm->isValid()) {
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

        return $this->render('admin/produit/edit.html.twig', array(
            'categories' => $categories,
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
     * @param Produit $produit
     * @param $allergene
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/{id}/{allergene}", name="produit_delete_allergene")
     */
    public function deleteAllergene(Produit $produit, $allergene)
    {
        $diff = array_diff($produit->getAllergenes(), array($allergene));

        $produit->setAllergenes($diff);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('produit_edit', array('id' => $produit->getId()));
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
            $em->remove($produit);
            $em->flush($produit);
        }

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
