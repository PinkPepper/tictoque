<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commande;
use AppBundle\Entity\CommandeMenu;
use AppBundle\Entity\CommandeProduit;
use AppBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Commande controller.
 *
 * @Route("/commande")
 */
class CommandeController extends Controller
{
    /**
     * Choisir ses options de commande
     * @Route("/", name="commande_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $prix = $session->get('prix');
        if($prix == 5 || $prix === null) //panier vide
        {
            return $this->redirectToRoute('index_panier');
        }

        //TODO moyen de paiement
        return $this->render('frontoffice/commande/index.html.twig');
    }

    /**
     * @Route("/results/{adresse}", name="commande_point_relais")
     */
    public function resultAction($adresse)
    {
        $geocoder = $this->container->get('app.geocoder_service');
        $pointsRelais = $geocoder->getPointsRelais($adresse);

        return $this->render('frontoffice/commande/results.html.twig', array(
            'pointsRelais'=>$pointsRelais
        ));
    }

    /**
     * Succes de la commande
     * @Route("/validation_panier/success", name="commande_succes")
     * @Method("GET")
     */
    public function SuccesAction(Request $request)
    {
        $em = $this->getDoctrine();
        $pointRelais = $request->cookies->get('pointRelais');
        $pointRelais = $em->getRepository('AppBundle:PointRelais')->find($pointRelais);

        $em = $em->getManager();
        $session = $request->getSession();

        $prix = $session->get('prix');

        $commande = new Commande();
        $commande->setUser($this->getUser());
        $commande->setPrix($prix);
        $commande->setAdresse($pointRelais->getAdresse());

        $em->persist($commande);
        $em->flush();

        //TODO informer le livreur
        //TODO mettre à jour la quantité de produit dans la base selon la commande

        $em = $this->getDoctrine();
        $panier = $session->all();

        $produits = array();

        foreach ($panier as $key => $value)
        {
            if (strpos($key, 'produit') !== false && (strpos($key, 'produit') == 0)) //produit
            {
                    $quantite = $value['quantite'];
                    $id = explode("_", $key);
                    $id = $id[1];
                    array_push($produits, array($em->getRepository('AppBundle:Produit')->find($id), $quantite));

                    $commandeProduit = new CommandeProduit();
                    $commandeProduit->setCommande($commande);
                    $commandeProduit->setProduits($em->getRepository('AppBundle:Produit')->find($id));
                    $commandeProduit->setQuantiteCommandee($quantite);
                    $em->getManager()->persist($commandeProduit);
                    $em->getManager()->flush();
            }

            else if ($key != "menu" && (strpos($key, 'menu') !== false) && (strpos($key, 'menu') == 0)) //menu
            {
                $menu = new Menu();
                $menu->setMenu($value['entree'], $value['plat'], $value['dessert'], $value['boisson'], $value['prix'], $value['quantite'], $this->getUser());

                $em->getManager()->persist($menu);
                $em->getManager()->flush();

                /* Création commandeMenu */
                $commandeMenu = new CommandeMenu();
                $commandeMenu->setCommande($commande);
                $commandeMenu->setMenus($menu);
                $em->getManager()->persist($commandeMenu);
                $em->getManager()->flush();

            }
        }

        /* ****** PAGE SUCCES ****** */
        $page_succes = $this->preparePageSucces($session);
        $produits_panier = $page_succes[0];
        $menus = $page_succes[1];
        /* */

        /* reset le panier */
        $session = $request->getSession();
        $session->clear();

        /* Triche pour regler un problème */
        $badCommande = $em->getRepository('AppBundle:Commande')->findByPrix(null);
        foreach ($badCommande as $bc){
            $em->getManager()->remove($bc);
            $em->getManager()->flush();
        }

        return $this->render('frontoffice/commande/succes.html.twig', array(
            'commande'=>$commande,
            'pointRelais'=>$pointRelais,
            'produits'=>$produits_panier,
            'menus'=>$menus,
            'prix'=>$prix
        ));
    }

    function preparePageSucces($session)
    {
        $em = $this->getDoctrine()->getManager();
        $panier = $session->all();
        $produits_panier = array();
        $menus = array();

        foreach ($panier as $key => $value)
        {
            if (strpos($key, 'produit') !== false && (strpos($key, 'produit') == 0)) //produit
            {
                $id = explode("_", $key);
                $id = $id[1];
                array_push($produits_panier, array($em->getRepository('AppBundle:Produit')->find($id), $value['quantite']));
            }
            else if ($key != "menu" && (strpos($key, 'menu') !== false) && (strpos($key, 'menu') == 0)) //menu
            {
                $id = explode("_", $key);
                $id = $id[1];

                if ($value['entree'] != null) {
                    $entree = $em->getRepository('AppBundle:Produit')->find($value['entree']);
                } else {
                    $entree = null;
                }
                if ($value['plat'] != null) {
                    $plat = $em->getRepository('AppBundle:Produit')->find($value['plat']);
                } else {
                    $plat = null;
                }

                if ($value['dessert'] != null) {
                    $dessert = $em->getRepository('AppBundle:Produit')->find($value['dessert']);
                } else {
                    $dessert = null;
                }
                if ($value['boisson'] != null) {
                    $boisson = $em->getRepository('AppBundle:Produit')->find($value['boisson']);
                } else {
                    $boisson = null;
                }
                array_push($menus, array('id' => $id, 'entree' => $entree, 'plat' => $plat, 'dessert' => $dessert, 'boisson' => $boisson, 'quantite' => $value['quantite'], 'prix' => $value['prix']));
            }
        }

        return array($produits_panier, $menus);
    }

}
