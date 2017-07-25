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
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $prix = $session->get('prix');
        if($prix == 5 || $prix === null) //panier vide
        {
            return $this->redirectToRoute('index_panier');
        }

        return $this->render('frontoffice/commande/index.html.twig');
    }

    /**
     * Succes de la commande
     * @Route("/validation_panier/success", name="commande_succes")
     * @Method("GET")
     */
    public function SuccesAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine();

        $cookies = $request->cookies;
        $date = $cookies->get('date');

        $pointRelais = $session->get("pointRelais");
        if($pointRelais == "" | $pointRelais === null)
        {
            return $this->redirectToRoute("commande_index");
        }
        $pointRelais = $em->getRepository('AppBundle:PointRelais')->find($pointRelais);

        $prix = $session->get('prix');
       // $date = $session->get('dateLivraison');

        $commande = $this->setCommande($pointRelais, $prix, $date);
        if($commande === null){
            $this->addFlash(
                'notice',
                'La date de livraison n\' est pas valide.'
            );
            return $this->redirectToRoute('commande_index');
        }
        $this->setContenuCommande($request, $commande);

        //TODO informer le livreur
        //TODO mettre à jour la quantité de produit dans la base selon la commande


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

        $this->setMessage($commande, $pointRelais, $produits_panier, $menus, $prix);

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
                array_push($menus, array('id' => $id, 'entree' => $entree, 'plat' => $plat, 'dessert' => $dessert, 'boisson' => $boisson, 'quantite' => $value['quantite'], 'prix' => $value['prix'], 'type' => $value['type']));
            }
        }

        return array($produits_panier, $menus);
    }


    private function setCommande($pointRelais, $prix, $date)
    {
        $commande = new Commande();
        $commande->setUser($this->getUser());
        $commande->setPrix($prix);
        $commande->setAdresse($pointRelais->getAdresse());

        $today = new \DateTime();
        $date = new \DateTime($date);


        if($today->format('Y-m-d') == $date->format('Y-m-d')){
            $hours = (new \DateTime())->format('H');
            if($hours < 10)
            {
                $commande->setDateLivraison(new \DateTime());
            }
            else
            {
                $commande->setDateLivraison((new \DateTime())->add(new \DateInterval('P1D')));
            }
        }
        else if($today->format('Y-m-d') < $date->format('Y-m-d'))
        {
            $commande->setDateLivraison($date);
        }
        else{
            return null;
        }


        $this->getDoctrine()->getManager()->persist($commande);
        $this->getDoctrine()->getManager()->flush();

        return $commande;
    }

    private function setContenuCommande(Request $request, $commande)
    {
        $session = $request->getSession();
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

                $produit = $em->getRepository('AppBundle:Produit')->find($id);
                array_push($produits, array($produit, $quantite));

                $commandeProduit = new CommandeProduit();
                $commandeProduit->setCommande($commande);
                $commandeProduit->setProduits($produit);
                $commandeProduit->setQuantiteCommandee($quantite);

                $produit->setQuantite($produit->getQuantite()-$quantite);

                $em->getManager()->persist($commandeProduit);
                $em->getManager()->persist($produit);
                $em->getManager()->flush();
            }

            else if ($key != "menu" && (strpos($key, 'menu') !== false) && (strpos($key, 'menu') == 0)) //menu
            {
                $menu = new Menu();
                $menu->setMenu($value['entree'], $value['plat'], $value['dessert'], $value['boisson'], $value['prix'], $value['quantite'], $this->getUser());
                $em->getManager()->persist($menu);

                if($value['entree'] !== null)
                {
                    $produit = $em->getRepository('AppBundle:Produit')->find($value['entree']);
                    $produit->setQuantite($produit->getQuantite()-1);
                    $em->getManager()->persist($produit);
                }

                if($value['plat'] !== null)
                {
                    $produit = $em->getRepository('AppBundle:Produit')->find($value['plat']);
                    $produit->setQuantite($produit->getQuantite()-1);
                    $em->getManager()->persist($produit);
                }

                if($value['dessert'] !== null)
                {
                    $produit = $em->getRepository('AppBundle:Produit')->find($value['dessert']);
                    $produit->setQuantite($produit->getQuantite()-1);
                    $em->getManager()->persist($produit);
                }

                if($value['boisson'] !== null)
                {
                    $produit = $em->getRepository('AppBundle:Produit')->find($value['boisson']);
                    $produit->setQuantite($produit->getQuantite()-1);
                    $em->getManager()->persist($produit);
                }

                /* Création commandeMenu */
                $commandeMenu = new CommandeMenu();
                $commandeMenu->setCommande($commande);
                $commandeMenu->setMenus($menu);
                $em->getManager()->persist($commandeMenu);

                $em->getManager()->flush();
            }
        }
    }

    private function setMessage($commande, $pointRelais, $produits_panier, $menus, $prix)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation de votre commande')
            ->setFrom('no-reply@lamourfood.fr')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/confirmation.html.twig',
                    array(
                        'commande'=>$commande,
                        'pointRelais'=>$pointRelais,
                        'produits'=>$produits_panier,
                        'menus'=>$menus,
                        'prix'=>$prix
                    )
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
    }
}
