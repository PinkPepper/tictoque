<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Menu;
use AppBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PanierController
 * @package AppBundle\Controller
 * @Route("/panier")
 */
class PanierController extends Controller
{
    /**
     * @Route("/", name="index_panier")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine();
        
        $session = $request->getSession();
        $panier = $session->get('panier');

        dump($panier);

        $menus_id = $panier['menus'];
        $produits_id = $panier['produits'];

        $menus = array();
        $produits = array();

        if($produits_id)
        {
            foreach ($produits_id as $produit)
            {
                $produit = array($em->getRepository("AppBundle:Produit")->find($produit[0]), $produit[1]);
                array_push($produits, $produit);
            }
        }

        return $this->render('frontoffice/panier/index.html.twig', array('panier'=>$panier, 'user'=>$user, 'produits'=>$produits, 'menus'=>$menus_id));
    }

    /**
     * @Route("/ajoutProduitAuPanier/{produit}", name="ajout_panier")
     */
    public function ajouterProduitAuPanier(Request $request, Produit $produit)
    {
        $doublon = false;
        $session = $request->getSession();
        $panier = $session->get('panier');

        if(!$panier)
        {
            $panier = array('menus'=>array(), 'produits'=>array());
        }

        for ($i = 0; $i<sizeof($panier['produits']) ; $i++)
        {
            if($panier['produits'][$i][0] == $produit->getId())
            {
                $panier['produits'][$i][1] = $panier['produits'][$i][1] + 1;
                $session->set('panier', $panier);
                $doublon = true;
                break;
            }
        }

        if(!$doublon)
        {
            array_push($panier, (array_push($panier['produits'], array($produit->getId(), 1))));
            $session->set('panier', $panier);
        }

        return $this->render('frontoffice/produit/success.html.twig');
    }

    /**
     * @Route("/ajoutExemplaireProduitAuPanier/{produit}", name="ajout_exemplaire_panier")
     */
    public function ajoutExemplaireProduit(Request $request, Produit $produit)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        $nombre = 0;

        if(!$panier)
        {
            throw  new NotFoundHttpException();
        }

        for ($i = 0; $i<sizeof($panier['produits']) ; $i++)
        {
            if ($panier['produits'][$i][0] == $produit->getId()) {
                $panier['produits'][$i][1] = $panier['produits'][$i][1] + 1;

                $nombre = $panier['produits'][$i][1] ;
                $session->set('panier', $panier);
                break;
            }
        }

        return $this->render('frontoffice/produit/nombre.html.twig', array('nombre'=>$nombre));
    }

    /**
     * @Route("/retirerExemplaireProduitAuPanier/{produit}", name="retrait_exemplaire_panier")
     */
    public function retirerExemplaireProduit(Request $request, Produit $produit)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        $nombre = 0;

        if(!$panier)
        {
            throw  new NotFoundHttpException();
        }

        for ($i = 0; $i<sizeof($panier['produits']) ; $i++)
        {
            if ($panier['produits'][$i][0] == $produit->getId()) {
                $panier['produits'][$i][1] = $panier['produits'][$i][1] - 1;

                $nombre = $panier['produits'][$i][1] ;
                if($nombre == 0)
                {
                    //TODO effacer le produit du panier ? != ignorer produit quantité 0 du panier
                    //   $panier['produits'] =   array_diff($panier['produits'], $panier['produits'][$i]); //marche pas
                    //il se passe rien du coup cest génial
                    //suffit de pas afficher un produit à 0 et de ne pas le prendre en compte dans la commande
                    //merci tout le monde pour votre soutient
                }
                $session->set('panier', $panier);
                break;
            }
        }

        return $this->render('frontoffice/produit/nombre.html.twig', array('nombre'=>$nombre));
    }


    /**
     * @Route("/ajoutExemplaireMenuAuPanier/{menu}", name="ajout_exemplaire_menu_panier")
     */
    public function ajoutExemplaireMenu(Request $request, $menu)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        $nombre = 0;

        if(!$panier)
        {
            throw  new NotFoundHttpException();
        }

        for ($i = 0; $i<sizeof($panier['menus']) ; $i++)
        {
            if ($panier['menus'][$i]["id"] == $menu) {
                $panier['menus'][$i]["quantite"] = $panier['menus'][$i]["quantite"] + 1;

                $nombre = $panier['menus'][$i]["quantite"] ;
                $session->set('panier', $panier);
                break;
            }
        }

        return $this->render('frontoffice/produit/nombre.html.twig', array('nombre'=>$nombre));
    }

    /**
     * @Route("/retirerExemplaireMenuAuPanier/{menu}", name="retrait_exemplaire_menu_panier")
     */
    public function retirerExemplaireMenu(Request $request, $menu)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        $nombre = 0;

        if(!$panier)
        {
            throw  new NotFoundHttpException();
        }

        for ($i = 0; $i<sizeof($panier['menus']) ; $i++)
        {
            if ($panier['menus'][$i]["id"] == $menu) {
                $panier['menus'][$i]["quantite"] = $panier['menus'][$i]["quantite"] - 1;

                $nombre = $panier['menus'][$i]["quantite"] ;
                if($nombre == 0)
                {
                    //TODO effacer le menu du panier ? != ignorer menu quantité 0 du panier
                    //   $panier['produits'] =   array_diff($panier['produits'], $panier['produits'][$i]); //marche pas
                    //il se passe rien du coup cest génial
                    //suffit de pas afficher un produit à 0 et de ne pas le prendre en compte dans la commande
                    //merci tout le monde pour votre soutient
                }
                $session->set('panier', $panier);
                break;
            }
        }

        return $this->render('frontoffice/produit/nombre.html.twig', array('nombre'=>$nombre));
    }

    /**
     * @Route("/ValidationPanier", name="validation_panier")
     */
    public function ValidationDuPanierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $panier = $session->get('panier');

        /* On crée les menus dans la base */
        foreach($panier['menus'] as $panier)
        {
            $menu = new Menu();

            $menu->setMenu($panier['entree'], $panier['plat'], $panier['dessert'], $panier['boisson'], $panier['prix'], $panier['quantite'], $this->getUser());
            $em->persist($menu);
            $em->flush();
        }

        //TODO créer la commande

        return $this->render('frontoffice/panier/commande.html.twig');
    }
}