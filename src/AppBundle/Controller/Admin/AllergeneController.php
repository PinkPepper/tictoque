<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Allergene;
use AppBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * allergene controller.
 *
 * @Route("admin/allergene")
 */
class AllergeneController extends Controller
{

    /**
     * Lists all allergene entities.
     *
     * @Route("/", name="admin_allergene_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $allergene = new Allergene();
        $form = $this->createForm('AppBundle\Form\AllergeneType', $allergene);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $allergenes = $em->getRepository('AppBundle:allergene')->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($allergene);
            $em->flush($allergene);

            return $this->redirectToRoute('admin_allergene_show', array('id' => $allergene->getId()));
        }

        return $this->render('admin/allergene/index.html.twig', array(
            'allergenes' => $allergenes,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new allergene entity.
     *
     * @Route("/new", name="admin_allergene_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $allergene = new Allergene();
        $form = $this->createForm('AppBundle\Form\AllergeneType', $allergene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($allergene);
            $em->flush($allergene);

            return $this->redirectToRoute('admin_allergene_show', array('id' => $allergene->getId()));
        }

        return $this->render('admin/allergene/new.html.twig', array(
            'allergene' => $allergene,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a allergene entity.
     *
     * @Route("/{id}", name="admin_allergene_show")
     * @Method("GET")
     */
    public function showAction(Allergene $allergene)
    {
        $deleteForm = $this->createDeleteForm($allergene);

        return $this->render('admin/allergene/show.html.twig', array(
            'allergene' => $allergene,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing allergene entity.
     *
     * @Route("/{id}/edit", name="admin_allergene_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Allergene $allergene)
    {
        $deleteForm = $this->createDeleteForm($allergene);
        $editForm = $this->createForm('AppBundle\Form\AllergeneType', $allergene);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_allergene_edit', array('id' => $allergene->getId()));
        }

        return $this->render('admin/allergene/edit.html.twig', array(
            'allergene' => $allergene,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a allergene entity.
     *
     * @Route("/{id}", name="admin_allergene_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Allergene $allergene)
    {
        $form = $this->createDeleteForm($allergene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $produits = $allergene->getProduits();

            foreach($produits as $produit)
            {
                $allergene->removeProduit($produit);
                $produit->RemoveAllergene($allergene);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($allergene);
            $em->flush($allergene);
        }

        return $this->redirectToRoute('admin_allergene_index');
    }

    /**
     * @Route("/delete/delete/{id}", name="admin_allergene_delete_index")
     */
    public function deleteIndexAction(Allergene $allergene)
    {
        $produits = $allergene->getProduits();

        foreach($produits as $produit)
        {
            $allergene->removeProduit($produit);
            $produit->RemoveAllergene($allergene);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($allergene);
        $em->flush($allergene);

        return $this->redirectToRoute('admin_allergene_index');
    }

    /**
     * Creates a form to delete a allergene entity.
     *
     * @param allergene $allergene The allergene entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Allergene $allergene)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_allergene_delete', array('id' => $allergene->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/allergene/enlever_produit/{allergene}/{produit}", name="allergene_enlever_produit")
     */
    public function enleverProduitAction(Allergene $allergene, Produit $produit)
    {
        $allergene->removeProduit($produit);
        $produit->RemoveAllergene($allergene);

        $this->getDoctrine()->getEntityManager()->flush();

        return $this->redirectToRoute('admin_allergene_show', array('id'=>$allergene->getId()));
    }


}
