<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ProfileCustomController
 * @package AppBundle\Controller
 *
 * @Route("/profile")
 */
class ProfileCustomController extends Controller
{
    /**
     * @Route("/index", name="profile_edit_index")
     */
    public function editAction()
    {
        return $this->render('frontoffice/profile/edit.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/edit", name="profile_edit_show")
     */
    public function resetAllergenesAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm('AppBundle\Form\UserProfileEditType', $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('profile_edit_show');
        }

        return $this->render('frontoffice/profile/edit_show.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    public function historiqueCommandeAction(){
        
    }
}


