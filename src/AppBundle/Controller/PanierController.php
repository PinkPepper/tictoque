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
            if (strpos($key, 'produit') !== false && (strpos($key, 'produit') == 0)) //produit
            {
                $id = explode("_", $key);
                $id = $id[1];
                array_push($produits, array($em->find($id), $value['quantite']));
            }
            else if ($key != "menu" && (strpos($key, 'menu') !== false) && (strpos($key, 'menu') == 0)) //menu
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

                array_push($menus, array('id'=>$id, 'entree'=>$entree, 'plat'=>$plat, 'dessert'=>$dessert, 'boisson'=>$boisson, 'quantite'=>$value['quantite'], 'prix'=>$value['prix'], 'type'=>$value['type']));

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
        $reponse = "a été ajouté au panier avec succès.";

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
            $quantiteFutur = $pProduit['quantite'] + 1;
            if($quantiteFutur <= $produit->getQuantite())
            {
                $pProduit['quantite'] = $quantiteFutur;
                $session->set('produit_' . $produit->getId(), $pProduit);
                $prix = $prix + $produit->getPrix();
                $session->set('prix', $prix);
            }
            else
            {
                $reponse = "n'est plus disponible, et n'a pas pu être ajouté à votre panier.";
            }
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

        return $this->render('frontoffice/produit/success.html.twig', array(
            "reponse"=>$reponse,
            "produit"=>$produit
        ));
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

    /**
     * @Route("/doMenus", name="do_menus")
     */
    public function doMenusAction(Request $request)
    {
        $session = $request->getSession();
        $menus = $this->getMenus($request);

        for ($i = 0 ; $i < sizeof($menus) ; $i++)
        {
            $number = $i;
            while($session->get('menu_'.$number) !== null){
                $number++;
            }

            $session->set('menu_'.$number, $menus[$i]);

        }

        dump($session->all());

        return $this->redirectToRoute("index_panier");
    }

    public function getMenus(Request $request)
    {
        $session = $request->getSession();
        $obj = $session->all();

        $menus = [];

        $entrees = $this->getDoctrine()->getRepository('AppBundle:Produit')->findBy(array('type'=>'entree'));
        $plats = $this->getDoctrine()->getRepository('AppBundle:Produit')->findBy(array('type'=>'plat'));
        $boissons = $this->getDoctrine()->getRepository('AppBundle:Produit')->findBy(array('type'=>'boisson'));
        $desserts = $this->getDoctrine()->getRepository('AppBundle:Produit')->findBy(array('type'=>'dessert'));
        $produits = [];

        foreach ($obj as $name=>$o) {
            preg_match('/^produit_/', $name, $matches);
            if($matches != null)
            {
                $produits[$name] = $o;
            }
        }

        while($this->verifyData($produits))
        {
            $entree = null; $plat = null; $dessert = null; $boisson = null;
            $produits_copy = $produits;

            foreach ($produits_copy as $i => $produit)
            {
                $id = explode("_", $i);
                $id = $id[1];

                if($entree == null && (in_array($id, $entrees)))
                {
                    $produits[$i]['quantite'] = $produits[$i]['quantite']-1;
                    $session->set($i, $produits[$i]);
                    if($produits[$i]['quantite'] == 0){
                        unset($produits[$i]);
                        $session->remove($i);
                    }
                    $entree = $id;
                }
                else if($plat == null && (in_array($id, $plats)))
                {
                    $produits[$i]['quantite'] = $produits[$i]['quantite']-1;
                    $session->set($i, $produits[$i]);
                    if($produits[$i]['quantite'] == 0){
                        unset($produits[$i]);
                        $session->remove($i);
                    }
                    $plat = $id;
                }
                else if($dessert == null && (in_array($id, $desserts)))
                {
                    $produits[$i]['quantite'] = $produits[$i]['quantite']-1;
                    $session->set($i, $produits[$i]);
                    if($produits[$i]['quantite'] == 0){
                        unset($produits[$i]);
                        $session->remove($i);
                    }
                    $dessert = $id;
                }
                else if($boisson == null && (in_array($id, $boissons))){
                    $produits[$i]['quantite'] = $produits[$i]['quantite']-1;
                    $session->set($i, $produits[$i]);
                    if($produits[$i]['quantite'] == 0){
                        unset($produits[$i]);
                        $session->remove($i);
                    }
                    $boisson = $id;
                }
            }

            array_push($menus, ['entree'=>$entree, 'plat'=>$plat, 'dessert'=>$dessert, 'boisson'=>$boisson, 'quantite'=>1, 'type'=>1, 'prix' => 2]);
        }

        $session->save();
        return $menus;
    }

    public function verifyData($produits)
    {
        $entree = false; $plat = false; $dessert = false; $boisson = false;
        foreach ($produits as $name=>$value)
        {
            $id = explode("_", $name);
            $id = $id[1];

            $pr = $this->getDoctrine()->getRepository('AppBundle:Produit')->find($id);

            if($pr->getType() == 'entree')
            {
              $entree = true;
            }
            else if($pr->getType() == 'plat')
            {
                $plat = true;
            }
            else if($pr->getType() == 'dessert')
            {
                $dessert = true;
            }
            else if($pr->getType() == 'boisson')
            {
                $boisson = true;
            }
        }

      return ($entree && $plat && $dessert && $boisson);
    }


}