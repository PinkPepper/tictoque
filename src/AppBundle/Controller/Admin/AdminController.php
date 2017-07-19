<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     *
     * @Route("/", name="admin_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if($this->getUser()->getRoles()[0] == "ROLE_LIVREUR"){
            return $this->redirectToRoute('admin_livreur_index');
        }

        $em = $this->getDoctrine();

        $commandeMois = $em->getRepository('AppBundle:Commande')->findForMonth();
        $commandeJour = $em->getRepository('AppBundle:Commande')->findByDay();
        $commandeStat = $em->getRepository('AppBundle:Commande')->statsMonth();
        $commandeYear = $em->getRepository('AppBundle:Commande')->findByYear("2017");


        $prix1 = 0;
        $prix2 = 0;

        $vente1 = 0;
        $vente2 = 0;

        for($i=0;$i<sizeof($commandeStat[0]);$i++){
            $prix1 += $commandeStat[0][$i]->getPrix();
        }

        for($i=0;$i<sizeof($commandeStat[0]);$i++){
            $vente1 += +1;
        }

        for($i=0;$i<sizeof($commandeStat[1]);$i++){
            $prix2 += $commandeStat[1][$i]->getPrix();
        }

        for($i=0;$i<sizeof($commandeStat[1]);$i++){
            $vente2 += +1;
        }

        $statRevenu = $prix1*100/$prix2;
        $statVente = $vente1*100+$vente2;

        $stringRevenu = "";
        $stringVentes = "";

        for($i=0;$i<12;$i++){
            $somme = 0;
            $nbVentes = 0;
            for($y=0;$y<sizeof($commandeYear[$i]);$y++){
                $somme += $commandeYear[$i][$y]->getPrix();
                $nbVentes += 1;
            }
            $stringRevenu .=  $somme."-";
            $stringVentes .=  $nbVentes."-";
        }


        return $this->render('backoffice/admin/index.html.twig',array(
            'commandeMois' => $commandeMois,
            'commandeJour' => $commandeJour,
            'statRevenu' =>$statRevenu,
            'statVente' => $statVente,
            'stringRevenu' => $stringRevenu,
            'stringVentes' => $stringVentes
        ));
    }

    /**
     *
     * @Route("/user", name="admin_user")
     * @Method("GET")
     */
    public function userAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('backoffice/admin/user/index.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/newUser", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($form->getData()->getRole());
            $user->addRole($form->getData()->getRole());
            if ($user->getImage() == "")
            {
                $user->setImage('userdefault.svg');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('backoffice/admin/user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/user/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('backoffice/admin/user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/user/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');
            $userManager->updatePassword($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('backoffice/admin/user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/user/{id}/delete", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_index');
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/user/{id}/delete/delete", name="user_delete_index")
     */
    public function deleteIndexAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin_index');
    }


    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
