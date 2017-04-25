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
        $session = $request->getSession();
        $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
        $panier = $session->all();

        $produits = array();
        $menus = array();

        foreach ($panier as $key => $value)
        {
            if (strpos($key, 'produit') !== false) //produit
            {
                $id = explode("_", $key);
                $id = $id[1];
                array_push($produits, array($em->find($id), $value['quantite']));
            }
            else if ($key != "menu" && (strpos($key, 'menu') !== false)) //menu
            {
                $id = explode("_", $key);
                $id = $id[1];

                if($value['entree'] != null)
                {
                    $entree = $em->find($value['entree']);
                }
                else{
                    $entree=null;
                }
                if($value['plat'] != null) {
                    $plat = $em->find($value['plat']);
                }
                else{
                    $plat = null;
                }

                if($value['dessert'] != null)
                {
                    $dessert = $em->find($value['dessert']);
                }
                else{
                    $dessert = null;
                }
                if($value['boisson'] != null)
                {
                    $boisson = $em->find($value['boisson']);
                }
                else{
                    $boisson = null;
                }
                    array_push($menus, array('id'=>$id, 'entree'=>$entree, 'plat'=>$plat, 'dessert'=>$dessert, 'boisson'=>$boisson, 'quantite'=>$value['quantite'], 'prix'=>$value['prix']));

            }
        }

        return $this->render('frontoffice/panier/index.html.twig', array(
            'panier'=>$panier,
            'user'=>$user,
            'produits'=>$produits,
            'menus'=>$menus
        ));
    }

    /**
     * @Route("/ajoutProduitAuPanier/{produit}", name="ajout_panier")
     */
    public function ajouterProduitAuPanier(Request $request, Produit $produit)
    {
        $session = $request->getsession();

        if($session->get('prix') == null)
        {
            $session->set('prix', 5); //5 -> frais de livraison
            $prix = 5;
        }
        else
        {
            $prix = $session->get('prix');
        }

        $pProduit = $session->get('produit_' . $produit->getId());
        if( $pProduit != null) //doublon
        {
            $pProduit['quantite'] = $pProduit['quantite'] + 1;
            $session->set('produit_' . $produit->getId(), $pProduit);
            $prix = $prix + $produit->getPrix();
            $session->set('prix', $prix);
        }
        else
        {
            $session->set('produit_' . $produit->getId(), array(
                'quantite'=>1,
                'prix'=>$produit->getPrix()
            ));
            $prix = $prix + $produit->getPrix();
            $session->set('prix', $prix);
        }

        dump($session->all());
        return $this->render('frontoffice/produit/success.html.twig');
    }

    /**
     * @Route("/ajoutExemplaireProduitAuPanier/{produit}", name="ajout_exemplaire_panier")
     */
    public function ajoutExemplaireProduit(Request $request, Produit $produit)
    {
        $session = $request->getsession();
        $pProduit = $session->get('produit_' . $produit->getId());
        if( $pProduit != null)
        {
            $pProduit['quantite'] += 1;
            $session->set('produit_' . $produit->getId(), $pProduit);
            $nombre = $pProduit['quantite'];

            $prix = $session->get("prix");
            $prix = $prix + $produit->getPrix();
            $session->set('prix', $prix);
        }
        else
        {
            throw  new NotFoundHttpException();
        }


        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
    }


    /**
     * @Route("/retirerExemplaireProduitAuPanier/{produit}", name="retrait_exemplaire_panier")
     */
    public function retirerExemplaireProduit(Request $request, Produit $produit)
    {
        $session = $request->getsession();
        $pProduit = $session->get('produit_' . $produit->getId());
        if( $pProduit != null)
        {
            $pProduit['quantite'] -= 1;
            $session->set('produit_' . $produit->getId(), $pProduit);
            $nombre = $pProduit['quantite'];

            $prix = $session->get("prix");
            $prix = $prix - $produit->getPrix();
            $session->set('prix', $prix);
        }
        else
        {
            throw  new NotFoundHttpException();
        }

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
    }


    /**
     * @Route("/ajoutExemplaireMenuAuPanier/{menu}", name="ajout_exemplaire_menu_panier")
     */
    public function ajoutExemplaireMenu(Request $request, $menu)
    {
        $session = $request->getsession();
        $pMenu = $session->get('menu_' . $menu);

        if( $pMenu != null)
        {
            $pMenu['quantite'] += 1;
            $session->set('menu_' . $menu, $pMenu);
            $nombre = $pMenu['quantite'];

            $prix = $session->get("prix");
            $prix = $prix + $pMenu['prix'];
            $session->set('prix', $prix);
        }
        else
        {
            throw  new NotFoundHttpException();
        }

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
    }

    /**
     * @Route("/retirerExemplaireMenuAuPanier/{menu}", name="retrait_exemplaire_menu_panier")
     */
    public function retirerExemplaireMenu(Request $request, $menu)
    {
        $session = $request->getsession();
        $pMenu = $session->get('menu_' . $menu);
        if( $pMenu != null)
        {
            $pMenu['quantite'] -= 1;
            $session->set('menu_' . $menu, $pMenu);
            $nombre = $pMenu['quantite'];

            $prix = $session->get("prix");
            $prix = $prix - $pMenu['prix'];
            $session->set('prix', $prix);
        }
        else
        {
            throw  new NotFoundHttpException();
        }

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
    }

    /**
     * @Route("/retirerProduitAuPanier/{produit}", name="delete_produit")
     */
    public function retirerProduit(Request $request, Produit $produit)
    {
        $session = $request->getsession();
        $pProduit = $session->get('produit_' . $produit->getId());

        if( $pProduit != null)
        {
            $quantite = $pProduit['quantite'];

            $prix = $session->get("prix");
            $prix = $prix - ($produit->getPrix() * $quantite);
            $session->set('prix', $prix);

            $session->remove('produit_' . $produit->getId());
        }

        $nombre = 0;

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
    }

    /**
     * @Route("/retirerMenuAuPanier/{menu}", name="delete_menu")
     */
    public function retirerMenu(Request $request, $menu)
    {
        $session = $request->getsession();


        $pMenu = $session->get('menu_' . $menu);

        if( $pMenu != null)
        {
            $quantite = $pMenu['quantite'];

            $prix = $session->get("prix");
            $prix = $prix - ($pMenu['prix'] * $quantite);
            $session->set('prix', $prix);

            $session->remove('menu_' . $menu);
        }

        $nombre = 0;

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
    }
}