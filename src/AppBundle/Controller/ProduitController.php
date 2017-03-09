<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Produit controller.
 *
 * @Route("/produit")
 */
class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="produit_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('AppBundle:Produit')->findAll();


        return $this->render('frontoffice/produit/index.html.twig', array(
            'produits' => $produits,

        ));
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="produit_show")
     * @Method("GET")
     */
    public function showAction(Produit $produit)
    {
        $autre="";

        if($produit->getType()=='entree'){
            $em = $this->getDoctrine()->getManager();
            $autre = $em->getRepository('AppBundle:Produit')->findByType('plat');
            shuffle($autre);
        }
        if($produit->getType()=='plat'){
            $em = $this->getDoctrine()->getManager();
            $autre = $em->getRepository('AppBundle:Produit')->findByType('dessert');
            shuffle($autre);
        }
        if($produit->getType()=='dessert'){
            $em = $this->getDoctrine()->getManager();
            $autre = $em->getRepository('AppBundle:Produit')->findByType('boisson');
            shuffle($autre);
        }
        if($produit->getType()=='boisson'){
            $em = $this->getDoctrine()->getManager();
            $autre = $em->getRepository('AppBundle:Produit')->findByType('plat');
            shuffle($autre);
        }

        return $this->render('frontoffice/produit/show.html.twig', array(
            'produit' => $produit,
            'autre' => $autre
        ));
    }

    /**
     * @Route("/ajoutPanier/{produit}", name="ajout_panier")
     */
    public function ajouterPanier(Request $request, Produit $produit)
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
     * @Route("/ajoutExemplairePanier/{produit}", name="ajout_exemplaire_panier")
     */
    public function ajoutExemplaire(Request $request, Produit $produit)
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
     * @Route("/retirerExemplairePanier/{produit}", name="retrait_exemplaire_panier")
     */
    public function retirerExemplaire(Request $request, Produit $produit)
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
                    $panier['produits'] = array_diff($panier['produits'], array($panier['produits'][$i]));
                }
                $session->set('panier', $panier);
                break;
            }
        }

        return $this->render('frontoffice/produit/nombre.html.twig', array('nombre'=>$nombre));
    }
}
