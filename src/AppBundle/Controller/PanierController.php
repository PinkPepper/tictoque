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

        if($menus_id)
        {
            $em = $this->getDoctrine()->getRepository('AppBundle:Produit');
            foreach ($menus_id as $menu)
            {
                    if($menu['entree'] != null)
                    {
                        $entree = $em->find($menu['entree']);
                    }
                    else{
                        $entree=null;
                    }
                    if($menu['plat'] != null) {
                        $plat = $em->find($menu['plat']);
                    }
                    else{
                        $plat = null;
                    }

                    if($menu['dessert'] != null)
                    {
                        $dessert = $em->find($menu['dessert']);
                    }
                    else{
                        $dessert = null;
                    }
                    if($menu['boisson'] != null)
                    {
                        $boisson = $em->find($menu['boisson']);
                    }
                    else{
                        $boisson = null;
                    }
                    array_push($menus, array('id'=>$menu['id'], 'entree'=>$entree, 'plat'=>$plat, 'dessert'=>$dessert, 'boisson'=>$boisson, 'quantite'=>$menu['quantite'], 'prix'=>$menu['prix']));
            }
        }


        return $this->render('frontoffice/panier/index.html.twig', array(
            'panier'=>$panier,
            'user'=>$user,
            'produits'=>$produits,
            'menus'=>$menus));
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

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
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

                $session->set('panier', $panier);
                break;
            }
        }

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
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

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
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

                $session->set('panier', $panier);
                break;
            }
        }

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
    }

    /**
     * @Route("/retirerProduitAuPanier/{produit}", name="delete_produit")
     */
    public function retirerProduit(Request $request, Produit $produit)
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
                $panier['produits'][$i][1] = 0;

                $nombre = $panier['produits'][$i][1] ;
                $session->set('panier', $panier);
                break;
            }
        }

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
    }

    /**
     * @Route("/retirerMenuAuPanier/{menu}", name="delete_menu")
     */
    public function retirerMenu(Request $request, $menu)
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
                $panier['menus'][$i]["quantite"] = 0;

                $nombre = $panier['menus'][$i]["quantite"] ;
                $session->set('panier', $panier);
                break;
            }
        }

        return $this->render('frontoffice/panier/nombre.html.twig', array('nombre'=>$nombre));
    }
}