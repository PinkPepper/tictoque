<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Livraison;
use AppBundle\Entity\PointRelais;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Livraison controller.
 *
 * @Route("admin/livraison")
 */
class LivraisonController extends Controller
{
    /**
     * Lists all livraison entities.
     *
     * @Route("/", name="livraison_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $livraisons = $em->getRepository('AppBundle:Livraison')->findAll();
        $pointRelais = $em->getRepository('AppBundle:PointRelais')->findAll();

        return $this->render('backoffice/admin/livraison/index.html.twig', array(
            'livraisons' => $livraisons,
            'pointRelais' => $pointRelais,
        ));
    }

    /**
     * Creates a new livraison entity.
     *
     * @Route("/new", name="livraison_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $livraison = new Livraison();
        $form = $this->createForm('AppBundle\Form\LivraisonType', $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($livraison);
            $em->flush();

            return $this->redirectToRoute('livraison_show', array('id' => $livraison->getId()));
        }

        return $this->render('backoffice/admin/livraison/new.html.twig', array(
            'livraison' => $livraison,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a livraison entity.
     *
     * @Route("/{id}", name="livraison_show")
     * @Method("GET")
     */
    public function showAction(Livraison $livraison)
    {
        $deleteForm = $this->createDeleteForm($livraison);

        return $this->render('backoffice/admin/livraison/show.html.twig', array(
            'livraison' => $livraison,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing livraison entity.
     *
     * @Route("/{id}/edit", name="livraison_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Livraison $livraison)
    {
        $deleteForm = $this->createDeleteForm($livraison);
        $editForm = $this->createForm('AppBundle\Form\LivraisonType', $livraison);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('livraison_edit', array('id' => $livraison->getId()));
        }

        return $this->render('backoffice/admin/livraison/edit.html.twig', array(
            'livraison' => $livraison,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing livraison entity.
     *
     * @Route("/{id}/relaisEdit", name="relais_edit")
     * @Method({"GET", "POST"})
     */
    public function relaisEditAction(Request $request, PointRelais $relais)
    {
        $deleteForm = $this->relaisCreateDeleteForm($relais);
        $editForm = $this->createForm('AppBundle\Form\PointRelaisType', $relais);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('relais_edit', array('id' => $relais->getId()));
        }

        return $this->render('backoffice/admin/relais/edit.html.twig', array(
            'relais' => $relais,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a livraison entity.
     *
     * @Route("/relais/{id}", name="relais_delete")
     * @Method("DELETE")
     */
    public function relaisDeleteAction(Request $request, PointRelais $pointRelais)
    {
        $form = $this->createDeleteForm($pointRelais);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pointRelais);
            $em->flush();
        }

        return $this->redirectToRoute('livraison_index');
    }


    /**
     * Deletes a livraison entity.
     *
     * @Route("/{id}", name="livraison_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Livraison $livraison)
    {
        $form = $this->createDeleteForm($livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($livraison);
            $em->flush();
        }

        return $this->redirectToRoute('livraison_index');
    }

    /**
     * Creates a form to delete a livraison entity.
     *
     * @param Livraison $livraison The livraison entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Livraison $livraison)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('livraison_delete', array('id' => $livraison->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function relaisCreateDeleteForm(PointRelais $pointRelais)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('relais_delete', array('id' => $pointRelais->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
