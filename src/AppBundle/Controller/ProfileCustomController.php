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
     * @param Request $request
     * @Route("/", name="profile_base")
     */
    public function showAction()
    {

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->findAll();
        $produits = $em->getRepository('AppBundle:Produit')->findAll();

        $finalTab = array_merge($produits,$articles);
        shuffle($finalTab);

        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user,
            'tableauSuggestion' => $finalTab
        ));
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

    /**
     * @Route("/historique_commande", name="historique_commande")
     */
    public function historiqueCommandeAction(Request $request)
    {
        $entree = null;
        $plat = null;
        $dessert = null;
        $boisson = null;

        $user = $this->getUser();

        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $array = array();

        $commandes = $user->getCommandes();

        foreach ($commandes as $commande)
        {
            $commandesMenu = $commande->getCommandesMenu();

            foreach ( $commandesMenu as $commandeMenu)
            {

                if($commandeMenu->getMenus()->getEntree() != null)
                {
                    $entree = $em->find($commandeMenu->getMenus()->getEntree());
                }
                if($commandeMenu->getMenus()->getPlat() != null)
                {
                    $plat = $em->find($commandeMenu->getMenus()->getPlat());
                }
                if($commandeMenu->getMenus()->getDessert() != null)
                {
                    $dessert = $em->find($commandeMenu->getMenus()->getDessert());
                }
                if($commandeMenu->getMenus()->getBoisson() != null)
                {
                    $boisson = $em->find($commandeMenu->getMenus()->getBoisson());
                }

                array_push($array, array('id_commandeMenu'=>$commandeMenu->getId(), 'entree'=>$entree, 'plat'=>$plat, 'dessert'=>$dessert, 'boisson'=>$boisson));
            }
        }

        return $this->render('frontoffice/profile/historique.html.twig', array(
            'user' => $user,
            'array'=>$array
        ));
    }
}


