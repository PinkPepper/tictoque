<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\PointRelais;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Pointrelai controller.
 *
 * @Route("/admin/pointrelais")
 */
class PointRelaisController extends Controller
{
    /**
     * Lists all pointRelai entities.
     *
     * @Route("/", name="admin_point_relais_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pointRelais = $em->getRepository('AppBundle:PointRelais')->findAll();

        return $this->render('backoffice/admin/pointRelais/index.html.twig', array(
            'pointsRelais' => $pointRelais,
        ));
    }

    /**
     * Creates a new PointRelais entity.
     *
     * @Route("/new", name="admin_point_relais_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $pointRelai = new PointRelais();
        $form = $this->createForm('AppBundle\Form\PointRelaisType', $pointRelai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $geocoder = $this->container->get('app.geocoder_service');
            $coord = $geocoder->adresseToCoord($pointRelai->getAdresse());
            $pointRelai->setLatitude($coord[0]);
            $pointRelai->setLongitude($coord[1]);

            $em->persist($pointRelai);
            $em->flush($pointRelai);

            return $this->redirectToRoute('admin_point_relais_index');
        }

        return $this->render('backoffice/admin/pointRelais/new.html.twig', array(
            'pointsRelais' => $pointRelai,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a pointRelai entity.
     *
     * @Route("/{id}", name="admin_point_relais_show")
     * @Method("GET")
     */
    public function showAction(PointRelais $pointRelai)
    {
        $deleteForm = $this->createDeleteForm($pointRelai);

        return $this->render('backoffice/admin/pointRelais/show.html.twig', array(
            'pointRelais' => $pointRelai,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing pointRelai entity.
     *
     * @Route("/{id}/edit", name="admin_point_relais_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PointRelais $pointRelai)
    {
        $deleteForm = $this->createDeleteForm($pointRelai);
        $editForm = $this->createForm('AppBundle\Form\PointRelaisType', $pointRelai);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $geocoder = $this->container->get('app.geocoder_service');
            $coord = $geocoder->adresseToCoord($pointRelai->getAdresse());
            $pointRelai->setLatitude($coord[0]);
            $pointRelai->setLongitude($coord[1]);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_point_relais_edit', array('id' => $pointRelai->getId()));
        }

        return $this->render('backoffice/admin/pointRelais/edit.html.twig', array(
            'pointRelais' => $pointRelai,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a pointRelai entity.
     *
     * @Route("/{id}", name="admin_point_relais_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PointRelais $pointRelai)
    {
        $form = $this->createDeleteForm($pointRelai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pointRelai);
            $em->flush();
        }

        return $this->redirectToRoute('admin_point_relais_index');
    }

    /**
     * Creates a form to delete a pointRelai entity.
     *
     * @param PointRelais $pointRelai The pointRelai entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PointRelais $pointRelai)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_point_relais_delete', array('id' => $pointRelai->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
